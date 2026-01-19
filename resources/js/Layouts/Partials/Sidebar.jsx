import ApplicationLogo from '@/Components/ApplicationLogo';
import NavLink from '@/Components/NavLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Card, CardContent } from '@/Components/ui/card';
import {
    IconBox,
    IconCalendarEvent,
    IconChartArrowsVertical,
    IconCreditCardPay,
    IconDoorEnter,
    IconDoorExit,
    IconLogout2,
    IconLogs,
    IconMenorah,
    IconMoneybag,
} from '@tabler/icons-react';

export default function Sidebar({ url }) {
    return (
        <nav className="flex flex-1 flex-col gap-y-6">
            <ApplicationLogo url={url} />

            <Card>
                <CardContent className="flex items-center gap-x-3 p-3">
                    <Avatar>
                        <AvatarImage src="#" />
                        <AvatarFallback>X</AvatarFallback>
                    </Avatar>

                    <div className="flex max-w-[160px] flex-col">
                        <span className="line-clamp-1 text-sm font-medium leading-relaxed tracking-tighter">
                            Ahihihihihihihihi.....
                        </span>

                        <span className="line-clamp-1 text-xs font-light">asdasd-343edsd3-3wasda3s-sds-a-ss</span>
                    </div>
                </CardContent>
            </Card>

            <ul role="list" className="flex flex-1 flex-col gap-y-2">
                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">General</div>
                <NavLink url="#" active={url.startsWith('/dashboard')} title="Dashboard" icon={IconBox} />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Master</div>
                <NavLink
                    url="#"
                    active={url.startsWith('/dashboard')}
                    title="Metode Pembayaran"
                    icon={IconCreditCardPay}
                />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Rencana</div>
                <NavLink url="#" active={url.startsWith('/goals')} title="Tujuan" icon={IconMoneybag} />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Pelacakan</div>
                <NavLink url="#" active={url.startsWith('/budgets')} title="Anggaran" icon={IconChartArrowsVertical} />
                <NavLink url="#" active={url.startsWith('/incomes')} title="Pemasukan" icon={IconDoorEnter} />
                <NavLink url="#" active={url.startsWith('/expenses')} title="Pengeluaran" icon={IconDoorExit} />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Aset dan Kewajiban</div>
                <NavLink url="#" active={url.startsWith('/net-worths')} title="Kekayaan Bersih" icon={IconMenorah} />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Laporan</div>
                <NavLink
                    url="#"
                    active={url.startsWith('/report-trackings')}
                    title="Laporan Pelacakan"
                    icon={IconLogs}
                />
                <NavLink
                    url="#"
                    active={url.startsWith('/annual-reports')}
                    title="Laporan Tahunan"
                    icon={IconCalendarEvent}
                />

                <div className="px-3 py-2 text-sm font-medium text-muted-foreground">Lainnya</div>
                <NavLink
                    as="button"
                    method="post"
                    url={route('logout')}
                    active={url.startsWith('/logout')}
                    title="Logout"
                    icon={IconLogout2}
                    className="w-full"
                />
            </ul>
        </nav>
    );
}
