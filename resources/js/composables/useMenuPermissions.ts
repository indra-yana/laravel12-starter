import useCan from "./useCan";
import { NavItem } from "@/types";

export default function useMenuPermissions() {
    const { canAny } = useCan()

    function hasPermissionForItem(item: NavItem): boolean {
        if (item?.route && canAny(item.route)) {
            return true
        }

        if (item.items && item.items.length > 0) {
            return item.items.some((child) => hasPermissionForItem(child))
        }

        return false
    }

    function hasPermissionForGroup(group: NavItem): boolean {
        return group?.items?.some((item) => hasPermissionForItem(item)) || false
    }

    return { hasPermissionForGroup, hasPermissionForItem }
}
