import AlertAction from "@/Components/AlertAction";
import Banner from "@/Components/Banner";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader";
import Filter from "@/Components/Datatable/Filter";
import PaginationTable from "@/Components/Datatable/PaginationTable";
import ShowFilter from "@/Components/Datatable/ShowFilter";
import EmptyState from "@/Components/EmptyState";
import HeaderTitle from "@/Components/HeaderTitle";
import { UseFilter } from "@/Components/Hooks/UseFilter";
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardFooter, CardHeader } from "@/Components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table";
import AppLayout from "@/Layouts/AppLayout"
import { Link } from "@inertiajs/react";
import { IconArrowsDownUp, IconMoneybag, IconPencil, IconPlus, IconShield, IconTrash } from "@tabler/icons-react";
import { useState } from "react";

export default function Index(props)
{
    const { data: roles, meta, links } = props.roles;

    const [params, setParams] = useState(props.state);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        })
    }

    UseFilter({
        route: route('roles.index'),
        values: params,
        only: ['roles'],
    })

    return (
        <div className="flex flex-col w-full pb-32 gap-y-6">
            <BreadcrumbHeader items={props.items} />

            <Banner title={props.pageSettings.banner.title} subtitle={props.pageSettings.banner.subtitle} />

            <Card>
                <CardHeader className='p-0'>
                    <div className='flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center'>
                        <HeaderTitle
                            title={props.pageSettings.title}
                            subtitle={props.pageSettings.subtitle}
                            icon={IconShield}
                        />

                        <Button variant='emerald' size='xl' asChild>
                            <Link href={route('roles.create')}>
                                <IconPlus className="size-4" />

                                Tambah Data
                            </Link>
                        </Button>
                    </div>

                    <Filter params={params} setParams={setParams} state={props.state} />
                    <ShowFilter params={params} />

                </CardHeader>

                <CardContent className='p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6'>
                    {roles.length === 0 ? (
                        <EmptyState
                            icon={IconShield}
                            title="Tidak ada data role"
                            subtitle="Buat role baru"
                        />

                    ) : (
                        <Table className='w-full'>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        <Button
                                            variant='ghos'
                                            className='inline-flex group'
                                            onClick={() => onSortable('id')}
                                        />
                                        #

                                        <span className="flex-none ml-2 rounded text-muted-foreground">
                                            <IconArrowsDownUp className="size-4" />
                                        </span>
                                    </TableHead>

                                    <TableHead>
                                        <Button
                                            variant='ghos'
                                            className='inline-flex group'
                                            onClick={() => onSortable('name')}
                                        />
                                        Nama Role

                                        <span className="flex-none ml-2 rounded text-muted-foreground">
                                            <IconArrowsDownUp className="size-4" />
                                        </span>
                                    </TableHead>

                                    <TableHead>
                                        <Button
                                            variant='ghos'
                                            className='inline-flex group'
                                            onClick={() => onSortable('guard_name')}
                                        />
                                        Guard

                                        <span className="flex-none ml-2 rounded text-muted-foreground">
                                            <IconArrowsDownUp className="size-4" />
                                        </span>
                                    </TableHead>

                                    <TableHead>
                                        <Button
                                            variant='ghos'
                                            className='inline-flex group'
                                            onClick={() => onSortable('created_at')}
                                        />
                                        Dibuat Pada

                                        <span className="flex-none ml-2 rounded text-muted-foreground">
                                            <IconArrowsDownUp className="size-4" />
                                        </span>
                                    </TableHead>

                                    <TableHead>Aksi</TableHead>

                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                {roles.map((role, index) => (
                                    <TableRow
                                        key={index}
                                    >
                                        <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>


                                        <TableCell>{role.name}</TableCell>
                                        <TableCell>{role.guard_name}</TableCell>
                                        <TableCell>{formatDateIndo(goal.created_at)}</TableCell>

                                        <TableCell>
                                            <div className="flex flex-items-center gap-x-1">
                                                <Button variant='blue' size='sm' asChild>
                                                    <Link href={route('roles.edit', [goal])}>
                                                        <IconPencil className="size-4" />
                                                    </Link>
                                                </Button>

                                                <AlertAction
                                                    trigger={
                                                        <Button variant='red' size='sm'>
                                                            <IconTrash className="size-4" />
                                                        </Button>
                                                    }
                                                    action={() => console.log('delete')}
                                                />
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </CardContent>

                <CardFooter className='flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row'>
                    <p className="text-sm text-muted-foreground">
                        Menampilkan <span className="font-medium text-emerald-600">{meta.from ?? 0}</span> dari {meta.total} role
                    </p>

                    <div className="overflow-x-auto">
                        {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                    </div>
                </CardFooter>
            </Card>
        </div>
    )
}


Index.layout = (page) => <AppLayout title={page.props.pageSettings.title} children={page}/>