import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconChevronDown } from '@tabler/icons-react';
import { useState } from 'react';
import DynamicIcon from './DynamicIcon';

export default function MenuLink({ menu, currentUrl, level = 0 }) {
    const [isOpen, setIsOpen] = useState(false);
    const hasChildren = menu.children && menu.children.length > 0;

    // Check if current route is active
    const isActive = menu.route && currentUrl.startsWith(`/${menu.route.split('.')[0]}`);

    // Indentation based on level
    const paddingLeft = {
        0: 'pl-3', // Menu level
        1: 'pl-6', // Submenu level
        2: 'pl-9', // Childmenu level
    };

    // If has children, render as collapsible button
    if (hasChildren) {
        return (
            <li>
                <button
                    onClick={() => setIsOpen(!isOpen)}
                    className={cn(
                        'flex w-full items-center justify-between rounded-lg py-2.5 pr-3 text-sm font-medium transition-all',
                        'hover:bg-accent/50 dark:hover:bg-accent/30',
                        'text-foreground dark:text-gray-200',
                        isOpen && 'bg-accent/30 dark:bg-accent/20',
                        paddingLeft[level],
                    )}
                >
                    <div className="flex items-center gap-x-3">
                        {menu.icon && (
                            <DynamicIcon
                                name={menu.icon}
                                className="size-4 shrink-0 text-muted-foreground dark:text-gray-400"
                            />
                        )}
                        <span className="dark:text-gray-200">{menu.name}</span>
                    </div>
                    <IconChevronDown
                        className={cn(
                            'size-4 shrink-0 text-muted-foreground transition-transform duration-200 dark:text-gray-400',
                            isOpen && 'rotate-180',
                        )}
                    />
                </button>

                {/* Children - Collapsed */}
                {isOpen && (
                    <ul className="mt-1 space-y-0.5">
                        {menu.children.map((child) => (
                            <MenuLink key={child.id} menu={child} currentUrl={currentUrl} level={level + 1} />
                        ))}
                    </ul>
                )}
            </li>
        );
    }

    // If has route, render as link
    if (menu.route) {
        return (
            <li>
                <Link
                    href={route(menu.route)}
                    className={cn(
                        'flex items-center gap-x-3 rounded-lg py-2.5 pr-3 text-sm font-medium transition-all',
                        'hover:bg-accent/50 dark:hover:bg-accent/30',
                        isActive
                            ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/30 dark:text-emerald-100'
                            : 'text-foreground dark:text-gray-200',
                        paddingLeft[level],
                    )}
                >
                    {menu.icon && (
                        <DynamicIcon
                            name={menu.icon}
                            className={cn(
                                'size-4 shrink-0',
                                isActive
                                    ? 'text-emerald-700 dark:text-emerald-400'
                                    : 'text-muted-foreground dark:text-gray-400',
                            )}
                        />
                    )}
                    <span>{menu.name}</span>
                </Link>
            </li>
        );
    }

    // No route and no children - just display text (shouldn't happen normally)
    return null;
}
