<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        make:service 
        {name? : name of the service}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create new service class.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (!$name) {
            return $this->error('Service name required.');
        }

        $explodedName = explode("/", $name);
        $explodedName = array_map(function ($item) {
            return Str::studly($item);
        }, $explodedName);
        $name = end($explodedName);

        array_pop($explodedName);
        $extraPath = implode("/", $explodedName);

        $name = $this->getCleanName($name);
        $fileName = $name['fileName'];
        $className = $name['className'];

        if (!$extraPath) {
            $folderName = explode("service", strtolower($className));
            $folderName = array_shift($folderName);
            $extraPath = Str::studly($folderName);
        }

        $dirPath = app_path() . "/Services/$extraPath";
        if (!File::isDirectory($dirPath)) {
            File::makeDirectory($dirPath, 0755, true);
        }

        $filePath = "$dirPath/{$fileName}";
        if (File::exists($filePath)) {
            return $this->error("Class {$fileName} already exists.");
        }

        $params = [
            "className" => $className,
            "nameSpace" => "App\Services\\" . str_replace("/", "\\", $extraPath),
        ];

        File::put($filePath, $this->stub($params));

        return $this->info("Class {$fileName} successfuly created.");
    }

    function getCleanName($className)
    {
        if (!Str::contains(strtolower($className), 'service')) {
            $className .= 'Service';
        }

        $className = Str::studly(
            Str::replace('service', 'Service', strtolower($className), false)
        );

        return [
            'className' => $className,
            'fileName' => "{$className}.php",
        ];
    }

    private function stub($params = [])
    {
        $className = $params['className'];
        $nameSpace = $params['nameSpace'];

        $file = "<?php\n\n";
        $file .= "namespace {$nameSpace};\n\n";
        $file .= "class {$className}\n";
        $file .= "{\n";
        $file .= "\t" . 'public function __construct()' . "\n";
        $file .= "\t" . "{\n";
        $file .= "\t" . "}\n\n";
        $file .= "}\n";

        return $file;
    }
}
