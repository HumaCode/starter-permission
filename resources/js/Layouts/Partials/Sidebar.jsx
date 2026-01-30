import ApplicationLogo from '@/Components/ApplicationLogo';
import MenuLink from '@/Components/MenuLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Card, CardContent } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/Components/ui/alert-dialog';
import { router, usePage } from '@inertiajs/react';
import { IconLogout2 } from '@tabler/icons-react';
import { useState, useMemo } from 'react';

/**
 * Struktur sidebar HARUS statis
 * Data menu boleh dinamis
 */
const CATEGORY_STRUCTURE = [
    { key: 'Master', label: 'MASTER' },
    { key: 'Role Permission', label: 'ROLE PERMISSION' },
    { key: 'Setting', label: 'SETTING' },
    { key: 'Reports', label: 'REPORTS' },
    { key: 'Others', label: 'OTHERS' },
];

export default function Sidebar({ auth, url }) {
    const [showLogoutDialog, setShowLogoutDialog] = useState(false);
    const { menus } = usePage().props;

    const user = auth?.user || auth;

    const handleLogout = () => {
        router.post(route('logout'));
    };

    /**
     * Normalisasi data menu
     */
    const menusArray = useMemo(() => {
        if (Array.isArray(menus)) return menus;
        if (menus?.data && Array.isArray(menus.data)) return menus.data;
        if (typeof menus === 'object' && menus !== null) return Object.values(menus);
        return [];
    }, [menus]);

    /**
     * Pisahkan menu:
     * - tanpa kategori (Dashboard, dsb)
     * - dengan kategori
     */
    const menusWithoutCategory = useMemo(
        () => menusArray.filter(menu => !menu?.metadata?.category),
        [menusArray]
    );

    const groupedMenus = useMemo(() => {
        return menusArray.reduce((acc, menu) => {
            const category = menu?.metadata?.category;
            if (!category) return acc;

            if (!acc[category]) acc[category] = [];
            acc[category].push(menu);
            return acc;
        }, {});
    }, [menusArray]);

    return (
        <>
            <nav className="flex h-full flex-col py-2">
                {/* Logo */}
                <div className="px-2">
                    <ApplicationLogo url={url} />
                </div>

                {/* User Card */}
                <div className="px-2 mt-3">
                    <Card className="border-border/50">
                        <CardContent className="flex items-center gap-x-3 p-3">
                            <Avatar className="h-10 w-10">
                                <AvatarImage src={user?.avatar} />
                                <AvatarFallback className="bg-emerald-100 text-emerald-700">
                                    {user?.name?.[0]?.toUpperCase() || 'U'}
                                </AvatarFallback>
                            </Avatar>

                            <div className="min-w-0 flex-1">
                                <div className="truncate text-sm font-semibold">
                                    {user?.name || 'Guest'}
                                </div>
                                <div className="truncate text-xs text-muted-foreground">
                                    {user?.email || ''}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <Separator className="my-3" />

                {/* Menu Scroll Area */}
                <div className="flex-1 overflow-y-auto px-2">
                    <ul className="space-y-4">

                        {/* Menu tanpa kategori (Dashboard, dsb) */}
                        {menusWithoutCategory.length > 0 && (
                            <li>
                                <ul className="space-y-0.5">
                                    {menusWithoutCategory.map(menu => (
                                        <MenuLink
                                            key={menu.id}
                                            menu={menu}
                                            currentUrl={url}
                                        />
                                    ))}
                                </ul>
                            </li>
                        )}

                        {menusWithoutCategory.length > 0 && <Separator />}

                        {/* Menu dengan kategori — STRUKTUR STATIS */}
                        {CATEGORY_STRUCTURE.map(section => {
                            const items = groupedMenus[section.key] ?? [];

                            // Pilihan desain:
                            // 1. return null → kategori hilang kalau kosong
                            // 2. render kosong → kategori tetap tampil
                            if (items.length === 0) return null;

                            return (
                                <li key={section.key}>
                                    <div className="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        {section.label}
                                    </div>

                                    <ul className="space-y-0.5">
                                        {items.map(menu => (
                                            <MenuLink
                                                key={menu.id}
                                                menu={menu}
                                                currentUrl={url}
                                            />
                                        ))}
                                    </ul>
                                </li>
                            );
                        })}
                    </ul>
                </div>

                <Separator className="my-3" />

                {/* Logout */}
                <div className="px-2 pb-2">
                    <button
                        onClick={() => setShowLogoutDialog(true)}
                        className="flex w-full items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50"
                    >
                        <IconLogout2 className="size-4" />
                        Logout
                    </button>
                </div>
            </nav>

            {/* Logout Dialog */}
            <AlertDialog open={showLogoutDialog} onOpenChange={setShowLogoutDialog}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Keluar dari aplikasi?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Anda akan logout dari akun <b>{user?.name}</b>.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Batal</AlertDialogCancel>
                        <AlertDialogAction onClick={handleLogout}>
                            Logout
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </>
    );
}
