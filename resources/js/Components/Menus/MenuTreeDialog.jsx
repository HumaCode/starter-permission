import { useState } from 'react';
import { IconChevronDown, IconChevronRight, IconGripVertical, IconEdit, IconTrash } from '@tabler/icons-react';
import DynamicIcon from '@/Components/DynamicIcon';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card } from '@/Components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';
import { ScrollArea } from '@/Components/ui/scroll-area';
import { cn } from '@/lib/utils';

// Menu Tree Node Component
function MenuTreeNode({ menu, level = 0, onEdit, onDelete }) {
    const [isExpanded, setIsExpanded] = useState(true);
    const hasChildren = menu.children && menu.children.length > 0;

    const levelColors = {
        menu: 'border-l-blue-500',
        submenu: 'border-l-green-500',
        childmenu: 'border-l-purple-500',
    };

    const levelBgColors = {
        menu: 'bg-blue-50 dark:bg-blue-950/30',
        submenu: 'bg-green-50 dark:bg-green-950/30',
        childmenu: 'bg-purple-50 dark:bg-purple-950/30',
    };

    return (
        <div className="space-y-2">
            <Card
                className={cn(
                    'border-l-4 transition-all hover:shadow-md',
                    levelColors[menu.level],
                    levelBgColors[menu.level]
                )}
            >
                <div className="flex items-center gap-3 p-3">
                    {/* Drag Handle */}
                    <button className="cursor-grab text-muted-foreground hover:text-foreground">
                        <IconGripVertical className="size-4" />
                    </button>

                    {/* Expand/Collapse Button */}
                    {hasChildren ? (
                        <button
                            onClick={() => setIsExpanded(!isExpanded)}
                            className="text-muted-foreground hover:text-foreground"
                        >
                            {isExpanded ? (
                                <IconChevronDown className="size-4" />
                            ) : (
                                <IconChevronRight className="size-4" />
                            )}
                        </button>
                    ) : (
                        <div className="w-4" />
                    )}

                    {/* Icon */}
                    {menu.icon && (
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950 dark:to-teal-950">
                            <DynamicIcon name={menu.icon} className="size-4 text-emerald-600 dark:text-emerald-400" />
                        </div>
                    )}

                    {/* Menu Info */}
                    <div className="flex-1 min-w-0">
                        <div className="flex items-center gap-2">
                            <h3 className="font-semibold text-sm text-foreground truncate">{menu.name}</h3>
                            <Badge variant="outline" className={cn(
                                'text-xs shrink-0',
                                menu.level === 'menu' && 'border-blue-300 bg-blue-100 text-blue-700 dark:border-blue-700 dark:bg-blue-950 dark:text-blue-300',
                                menu.level === 'submenu' && 'border-green-300 bg-green-100 text-green-700 dark:border-green-700 dark:bg-green-950 dark:text-green-300',
                                menu.level === 'childmenu' && 'border-purple-300 bg-purple-100 text-purple-700 dark:border-purple-700 dark:bg-purple-950 dark:text-purple-300'
                            )}>
                                {menu.level_label}
                            </Badge>
                        </div>
                        <div className="mt-1 flex items-center gap-3 text-xs text-muted-foreground">
                            {menu.route && (
                                <code className="rounded bg-muted px-1.5 py-0.5 font-mono truncate max-w-[200px]">
                                    {menu.route}
                                </code>
                            )}
                            <span className="shrink-0">Order: {menu.order}</span>
                            {menu.permissions_count > 0 && (
                                <span className="shrink-0">🔒 {menu.permissions_count}</span>
                            )}
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="flex items-center gap-1 shrink-0">
                        <Button
                            variant="ghost"
                            size="sm"
                            className="h-8 w-8 p-0"
                            onClick={() => onEdit && onEdit(menu)}
                        >
                            <IconEdit className="size-4" />
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            className="h-8 w-8 p-0 text-destructive hover:text-destructive"
                            onClick={() => onDelete && onDelete(menu)}
                        >
                            <IconTrash className="size-4" />
                        </Button>
                    </div>
                </div>
            </Card>

            {/* Children - Recursive */}
            {hasChildren && isExpanded && (
                <div className="ml-6 space-y-2 border-l-2 border-dashed border-muted pl-3">
                    {menu.children.map((child) => (
                        <MenuTreeNode
                            key={child.id}
                            menu={child}
                            level={level + 1}
                            onEdit={onEdit}
                            onDelete={onDelete}
                        />
                    ))}
                </div>
            )}
        </div>
    );
}

// Main Dialog Component
export default function MenuTreeDialog({ open, onOpenChange, menus }) {
    const handleEdit = (menu) => {
        console.log('Edit menu:', menu);
        // TODO: Implement edit functionality
    };

    const handleDelete = (menu) => {
        console.log('Delete menu:', menu);
        // TODO: Implement delete functionality
    };

    const totalMenus = menus?.length || 0;
    const activeMenus = menus?.filter(m => m.is_active).length || 0;
    const menusWithPermissions = menus?.filter(m => m.permissions_count > 0).length || 0;

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="max-w-4xl max-h-[90vh]">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        <span className='dark:text-white'>Menu Tree Structure</span>
                    </DialogTitle>
                    <DialogDescription>
                        Visualisasi struktur menu secara hierarki. Drag untuk mengatur ulang urutan.
                    </DialogDescription>
                </DialogHeader>

                {/* Legend */}
                <div className="flex flex-wrap gap-4 rounded-lg border bg-muted/50 p-3">
                    <div className="flex items-center gap-2">
                        <div className="h-6 w-1 rounded bg-blue-500" />
                        <span className="text-xs font-medium dark:text-white">Menu (Level 1)</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <div className="h-6 w-1 rounded bg-green-500" />
                        <span className="text-xs font-medium dark:text-white">Submenu (Level 2)</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <div className="h-6 w-1 rounded bg-purple-500" />
                        <span className="text-xs font-medium dark:text-white">Child Menu (Level 3)</span>
                    </div>
                </div>

                {/* Stats */}
                <div className="grid grid-cols-3 gap-4">
                    <div className="rounded-lg border bg-card p-3 text-center">
                        <p className="text-2xl font-bold">{totalMenus}</p>
                        <p className="text-xs text-muted-foreground">Total Menus</p>
                    </div>
                    <div className="rounded-lg border bg-card p-3 text-center">
                        <p className="text-2xl font-bold text-green-600">{activeMenus}</p>
                        <p className="text-xs text-muted-foreground">Active</p>
                    </div>
                    <div className="rounded-lg border bg-card p-3 text-center">
                        <p className="text-2xl font-bold text-blue-600">{menusWithPermissions}</p>
                        <p className="text-xs text-muted-foreground">With Permissions</p>
                    </div>
                </div>

                {/* Menu Tree */}
                <ScrollArea className="h-[400px] pr-4">
                    <div className="space-y-3">
                        {menus && menus.length > 0 ? (
                            menus.map((menu) => (
                                <MenuTreeNode
                                    key={menu.id}
                                    menu={menu}
                                    onEdit={handleEdit}
                                    onDelete={handleDelete}
                                />
                            ))
                        ) : (
                            <div className="flex flex-col items-center justify-center py-12 text-center">
                                <IconGripVertical className="mb-4 size-12 text-muted-foreground" />
                                <h3 className="mb-2 text-lg font-semibold dark:text-white">No Menus Found</h3>
                                <p className="text-sm text-muted-foreground">
                                    Create your first menu to see it here.
                                </p>
                            </div>
                        )}
                    </div>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}