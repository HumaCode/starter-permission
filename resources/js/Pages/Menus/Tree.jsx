import AppLayout from '@/Layouts/AppLayout';
import MenuTreeNode from '@/Components/MenuTreeDialog';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Link } from '@inertiajs/react';
import { IconArrowLeft, IconPlus, IconLayoutList } from '@tabler/icons-react';

export default function Tree({ pageSettings, menus, items }) {
    return (
        <AppLayout title={pageSettings.title}>
            {/* Header */}
            <div className="mb-6">
                {/* Breadcrumb */}
                <nav className="mb-4 flex items-center gap-2 text-sm text-muted-foreground">
                    {items.map((item, index) => (
                        <div key={index} className="flex items-center gap-2">
                            {item.href ? (
                                <>
                                    <Link href={item.href} className="hover:text-foreground">
                                        {item.label}
                                    </Link>
                                    <span>/</span>
                                </>
                            ) : (
                                <span className="text-foreground">{item.label}</span>
                            )}
                        </div>
                    ))}
                </nav>

                {/* Title & Actions */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">{pageSettings.banner.title}</h1>
                        <p className="text-muted-foreground">{pageSettings.banner.subtitle}</p>
                    </div>

                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={route('menus.index')}>
                                <IconLayoutList className="mr-2 size-4" />
                                Table View
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={route('menus.create')}>
                                <IconPlus className="mr-2 size-4" />
                                Add Menu
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>

            {/* Legend */}
            <Card className="mb-6">
                <CardHeader>
                    <CardTitle className="text-lg">Legend</CardTitle>
                    <CardDescription>Kode warna berdasarkan level menu</CardDescription>
                </CardHeader>
                <CardContent>
                    <div className="flex flex-wrap gap-4">
                        <div className="flex items-center gap-2">
                            <div className="h-8 w-1 rounded bg-blue-500" />
                            <span className="text-sm">Menu (Level 1)</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <div className="h-8 w-1 rounded bg-green-500" />
                            <span className="text-sm">Submenu (Level 2)</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <div className="h-8 w-1 rounded bg-purple-500" />
                            <span className="text-sm">Child Menu (Level 3)</span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* Menu Tree */}
            <div className="space-y-4">
                {menus && menus.length > 0 ? (
                    menus.map((menu) => (
                        <MenuTreeNode key={menu.id} menu={menu} />
                    ))
                ) : (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-12">
                            <IconLayoutList className="mb-4 size-12 text-muted-foreground" />
                            <h3 className="mb-2 text-lg font-semibold">No Menus Found</h3>
                            <p className="mb-4 text-sm text-muted-foreground">
                                Get started by creating your first menu.
                            </p>
                            <Button asChild>
                                <Link href={route('menus.create')}>
                                    <IconPlus className="mr-2 size-4" />
                                    Create Menu
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                )}
            </div>

            {/* Stats Card */}
            <Card className="mt-6">
                <CardContent className="flex items-center justify-between p-6">
                    <div>
                        <p className="text-sm text-muted-foreground">Total Menus</p>
                        <p className="text-2xl font-bold">{menus.length}</p>
                    </div>
                    <div>
                        <p className="text-sm text-muted-foreground">Active Menus</p>
                        <p className="text-2xl font-bold">
                            {menus.filter(m => m.is_active).length}
                        </p>
                    </div>
                    <div>
                        <p className="text-sm text-muted-foreground">With Permissions</p>
                        <p className="text-2xl font-bold">
                            {menus.filter(m => m.permissions_count > 0).length}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </AppLayout>
    );
}