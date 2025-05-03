/* eslint-disable @typescript-eslint/no-unused-vars */
import fs from 'fs/promises';
import path from 'path';
import { pathToFileURL } from 'url';

async function collectModuleAssetsPaths(paths, modulesPath) {
  modulesPath = path.join(__dirname, modulesPath);

  const moduleStatusesPath = path.join(__dirname, 'modules_statuses.json');

  try {
    // Read module_statuses.json
    const moduleStatusesContent = await fs.readFile(moduleStatusesPath, 'utf-8');
    const moduleStatuses = JSON.parse(moduleStatusesContent);

    // Read module directories
    const moduleDirectories = await fs.readdir(modulesPath);

    for (const moduleDir of moduleDirectories) {
      if (moduleDir === '.DS_Store' || moduleDir === '.gitignore') {
        // Skip .DS_Store directory
        continue;
      }

      // Check if the module is enabled (status is true)      
      if (moduleStatuses[moduleDir] === true) {
        const viteConfigPath = path.join(modulesPath, moduleDir, 'vite.config.js');

        try {
          await fs.access(viteConfigPath);
          // Convert to a file URL for Windows compatibility
          const moduleConfigURL = pathToFileURL(viteConfigPath);

          // Import the module-specific Vite configuration
          const moduleConfig = await import(moduleConfigURL.href);

          if (moduleConfig.paths && Array.isArray(moduleConfig.paths)) {
            paths.push(...moduleConfig.paths);
          }
        } catch (error) {
          // vite.config.js does not exist, skip this module
          console.log(`Unloaded module [${moduleDir}], please use load from main vite.config.js or load from modules.`);
        }
      }
    }
  } catch (error) {
    console.error(`Error reading module statuses or module configurations: ${error}`);
  }

  console.log(`Module assets loaded:`, paths);

  return paths;
}

export default collectModuleAssetsPaths;
