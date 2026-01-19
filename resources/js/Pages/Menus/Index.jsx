import AlertAction from "@/Components/AlertAction";
import Banner from "@/Components/Banner";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader";
import Filter from "@/Components/Datatable/Filter";
import PaginationTable from "@/Components/Datatable/PaginationTable";
import ShowFilter from "@/Components/Datatable/ShowFilter";
import EmptyState from "@/Components/EmptyState";
import HeaderTitle from "@/Components/HeaderTitle";
import DynamicIcon from "@/Components/DynamicIcon";
import { UseFilter } from "@/Components/Hooks/UseFilter";
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardFooter, CardHeader } from "@/Components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table";
import { Badge } from "@/Components/ui/badge";
import AppLayout from "@/Layouts/AppLayout";
import { Link, router } from "@inertiajs/react";
import {
    IconArrowsDownUp,
    IconPencil,
    IconPlus,
    IconTrash,
    IconMenu2,
    IconEye,
    IconCircleCheck,
    IconCircleX,
    IconLayoutGrid
} from "@tabler/icons-react";
import { useState } from "react";

export default function Index(props) {
    const { data: menus, meta, links } = props.menus;

    const [params, setParams] = useState(props.state);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };

    UseFilter({
        route: route('menus.index'),
        values: params,
        only: ['menus'],
    });

    const handleDelete = (menu) => {
        router.delete(route('menus.destroy', menu.id), {
            preserveScroll: true,
        });
    };

    const getLevelBadgeClass = (color) => {
        const variants = {
            blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            green: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            purple: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        };
        return variants[color] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    };

    return (
        <div className="flex flex-col w-full pb-32 gap-y-6">
            <BreadcrumbHeader items={props.items} />

            <Banner
                title={props.pageSettings.banner.title}
                subtitle={props.pageSettings.banner.subtitle}
            />

            <Card>
                <CardHeader className="p-0">
                    <div className="flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center">
                        <HeaderTitle
                            title={props.pageSettings.title}
                            subtitle={props.pageSettings.subtitle}
                            icon={IconMenu2}
                        />

                        <div className="flex gap-2">
                            {/* <Button variant="outline" size="xl" asChild>
                                <Link href={route('menus.tree')}>
                                    <IconLayoutGrid className="size-4" />
                                    Lihat Tree
                                </Link>
                            </Button> */}

                            <Button variant="emerald" size="xl" asChild>
                                <Link href={route('menus.create')}>
                                    <IconPlus className="size-4" />
                                    Tambah Menu
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <Filter params={params} setParams={setParams} state={props.state} />
                    <ShowFilter params={params} />
                </CardHeader>

                <CardContent className="p-0">
                    {menus.length === 0 ? (
                        <EmptyState
                            icon={IconMenu2}
                            title="Tidak ada data menu"
                            subtitle="Buat menu baru untuk memulai"
                        />
                    ) : (
                        <div className="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead className="w-12">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('id')}
                                            >
                                                #
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="min-w-[250px]">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('name')}
                                            >
                                                Nama Menu
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="w-32">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('level')}
                                            >
                                                Level
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="w-40">Parent</TableHead>

                                        <TableHead className="min-w-[180px]">Route</TableHead>

                                        <TableHead className="w-24 text-center">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('order')}
                                            >
                                                Order
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="w-32 text-center">Permissions</TableHead>

                                        <TableHead className="w-32">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('is_active')}
                                            >
                                                Status
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="w-40">
                                            <Button
                                                variant="ghost"
                                                className="inline-flex group"
                                                onClick={() => onSortable('created_at')}
                                            >
                                                Dibuat Pada
                                                <span className="flex-none ml-2 rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>

                                        <TableHead className="w-32 text-center">Aksi</TableHead>
                                    </TableRow>
                                </TableHeader>

                                <TableBody>
                                    {menus.map((menu, index) => (
                                        <TableRow key={index}>
                                            <TableCell className="font-medium text-center">
                                                {index + 1 + (meta.current_page - 1) * meta.per_page}
                                            </TableCell>

                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    {menu.icon && (
                                                        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950 dark:to-teal-950 border border-emerald-200 dark:border-emerald-800">
                                                            <DynamicIcon
                                                                name={menu.icon}
                                                                className="size-5 text-emerald-600 dark:text-emerald-400"
                                                            />
                                                        </div>
                                                    )}
                                                    <div className="flex flex-col">
                                                        <span className="font-medium">{menu.name}</span>
                                                        <span className="text-xs text-muted-foreground">
                                                            {menu.slug}
                                                        </span>
                                                    </div>
                                                </div>
                                            </TableCell>

                                            <TableCell>
                                                <Badge className={getLevelBadgeClass(menu.level_badge_color)}>
                                                    {menu.level_label}
                                                </Badge>
                                            </TableCell>

                                            <TableCell>
                                                {menu.parent ? (
                                                    <span className="text-sm font-medium">{menu.parent.name}</span>
                                                ) : (
                                                    <span className="text-sm text-muted-foreground italic">Root</span>
                                                )}
                                            </TableCell>

                                            <TableCell>
                                                {menu.route ? (
                                                    <code className="text-xs bg-muted/80 px-2 py-1 rounded font-mono">
                                                        {menu.route}
                                                    </code>
                                                ) : (
                                                    <span className="text-sm text-muted-foreground italic">No route</span>
                                                )}
                                            </TableCell>

                                            <TableCell className="text-center">
                                                <Badge
                                                    variant="outline"
                                                    className="font-mono font-semibold"
                                                >
                                                    {menu.order}
                                                </Badge>
                                            </TableCell>

                                            <TableCell className="text-center">
                                                <Badge
                                                    variant="outline"
                                                    className="font-semibold"
                                                >
                                                    {menu.permissions_count}
                                                </Badge>
                                            </TableCell>

                                            <TableCell>
                                                {menu.is_active ? (
                                                    <Badge className="gap-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <IconCircleCheck className="size-3" />
                                                        Aktif
                                                    </Badge>
                                                ) : (
                                                    <Badge className="gap-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <IconCircleX className="size-3" />
                                                        Nonaktif
                                                    </Badge>
                                                )}
                                            </TableCell>

                                            <TableCell>
                                                <div className="flex flex-col">
                                                    <span className="text-sm font-medium">{menu.created_at}</span>
                                                    <span className="text-xs text-muted-foreground">
                                                        {menu.created_at_human}
                                                    </span>
                                                </div>
                                            </TableCell>

                                            <TableCell>
                                                <div className="flex items-center justify-center gap-x-1">
                                                    {/* <Button variant="ghost" size="sm" asChild>
                                                        <Link href={route('menus.show', menu.id)}>
                                                            <IconEye className="size-4" />
                                                        </Link>
                                                    </Button> */}

                                                    <Button variant="blue" size="sm" asChild>
                                                        <Link href={route('menus.edit', menu.id)}>
                                                            <IconPencil className="size-4" />
                                                        </Link>
                                                    </Button>

                                                    <AlertAction
                                                        trigger={
                                                            <Button variant="red" size="sm">
                                                                <IconTrash className="size-4" />
                                                            </Button>
                                                        }
                                                        action={() => handleDelete(menu)}
                                                    />
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </div>
                    )}
                </CardContent>

                <CardFooter className="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row">
                    <p className="text-sm text-muted-foreground">
                        Menampilkan <span className="font-medium text-emerald-600">{meta.from ?? 0}</span> dari {meta.total} menu
                    </p>

                    <div className="overflow-x-auto">
                        {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                    </div>
                </CardFooter>
            </Card>
        </div>
    );
}

Index.layout = (page) => <AppLayout title={page.props.pageSettings.title} children={page} />;