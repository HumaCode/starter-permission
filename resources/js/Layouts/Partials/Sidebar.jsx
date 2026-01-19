import ApplicationLogo from '@/Components/ApplicationLogo';
import MenuLink from '@/Components/MenuLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Card, CardContent } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import { Link } from '@inertiajs/react';
import { IconLogout2 } from '@tabler/icons-react';

export default function Sidebar({ auth, url, menus = [] }) {
    const user = auth?.user || auth;

    // Separate menus with and without category
    const menusWithoutCategory = menus.filter((menu) => !menu.metadata?.category);
    const menusWithCategory = menus.filter((menu) => menu.metadata?.category);

    // Group menus with category
    const groupedMenus = menusWithCategory.reduce((acc, menu) => {
        const category = menu.metadata.category;
        if (!acc[category]) {
            acc[category] = [];
        }
        acc[category].push(menu);
        return acc;
    }, {});

    // Define category order
    const categoryOrder = ['Master', 'Settings', 'Reports', 'Others'];

    // Sort categories
    const sortedCategories = Object.keys(groupedMenus).sort((a, b) => {
        const indexA = categoryOrder.indexOf(a);
        const indexB = categoryOrder.indexOf(b);
        if (indexA === -1 && indexB === -1) return a.localeCompare(b);
        if (indexA === -1) return 1;
        if (indexB === -1) return -1;
        return indexA - indexB;
    });

    return (
        <nav className="flex flex-1 flex-col gap-y-4 py-2">
            {/* Logo */}
            <div className="px-2">
                <ApplicationLogo url={url} />
            </div>

            {/* User Card */}
            <div className="px-2">
                <Card className="border-border/50 dark:border-border/30">
                    <CardContent className="flex items-center gap-x-3 p-3">
                        <Avatar className="h-10 w-10">
                            <AvatarImage src={user?.avatar} />
                            <AvatarFallback className="bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-200">
                                {user?.name?.substring(0, 1).toUpperCase() || 'U'}
                            </AvatarFallback>
                        </Avatar>

                        <div className="flex min-w-0 flex-1 flex-col">
                            <span className="truncate text-sm font-semibold leading-tight text-foreground dark:text-gray-100">
                                {user?.name || 'Guest'}
                            </span>
                            <span className="truncate text-xs text-muted-foreground dark:text-gray-400">
                                {user?.email || ''}
                            </span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Separator className="dark:bg-border/30" />

            {/* Scrollable Menu Area */}
            <div className="flex-1 overflow-y-auto px-2">
                <ul role="list" className="space-y-4">
                    {/* Menus without category (Dashboard, etc) */}
                    {menusWithoutCategory.length > 0 && (
                        <li>
                            <ul className="space-y-0.5">
                                {menusWithoutCategory.map((menu) => (
                                    <MenuLink key={menu.id} menu={menu} currentUrl={url} />
                                ))}
                            </ul>
                        </li>
                    )}

                    {/* Add separator if both types exist */}
                    {menusWithoutCategory.length > 0 && sortedCategories.length > 0 && (
                        <li>
                            <Separator className="dark:bg-border/30" />
                        </li>
                    )}

                    {/* Menus with category */}
                    {sortedCategories.length > 0 ? (
                        sortedCategories.map((category) => (
                            <li key={category}>
                                {/* Category Header */}
                                <div className="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground dark:text-gray-500">
                                    {category}
                                </div>

                                {/* Category Menus */}
                                <ul className="space-y-0.5">
                                    {groupedMenus[category].map((menu) => (
                                        <MenuLink key={menu.id} menu={menu} currentUrl={url} />
                                    ))}
                                </ul>
                            </li>
                        ))
                    ) : menusWithoutCategory.length === 0 ? (
                        <li className="px-3 py-4 text-center text-sm italic text-muted-foreground dark:text-gray-500">
                            No menus available
                        </li>
                    ) : null}
                </ul>
            </div>

            <Separator className="dark:bg-border/30" />

            {/* Logout Button - Fixed at Bottom */}
            <div className="px-2 pb-2">
                <Link
                    as="button"
                    method="post"
                    href={route('logout')}
                    className="flex w-full items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 transition-all hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
                >
                    <IconLogout2 className="size-4 shrink-0" />
                    <span>Logout</span>
                </Link>
            </div>
        </nav>
    );
}
