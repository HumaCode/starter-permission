import ApplicationLogo from '@/Components/ApplicationLogo';
import InputError from '@/Components/InputError';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Checkbox } from '@/Components/ui/checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import { Link, useForm } from '@inertiajs/react';
import { Mail, Lock, ArrowRight, Sparkles } from 'lucide-react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <div className="flex flex-col gap-6">
            <Card className="overflow-hidden border-0 shadow-2xl">
                <CardContent className="grid p-0 md:grid-cols-2">
                    {/* Left Side - Form */}
                    <form onSubmit={onHandleSubmit} className="p-8 md:p-10">
                        <div className="flex flex-col gap-6">
                            {/* Header */}
                            <div className="flex flex-col gap-2">
                                <div className="flex items-center gap-2">
                                    <ApplicationLogo className="h-10 w-10" />
                                    {/* <div className="flex items-center gap-1">
                                        <Sparkles className="h-4 w-4 text-emerald-500" />
                                        <span className="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                            Cuan
                                        </span>
                                    </div> */}
                                </div>
                                <h1 className="text-3xl font-bold tracking-tight">Selamat Datang Kembali</h1>
                                <p className="text-sm text-muted-foreground">
                                    Masuk untuk melanjutkan pengelolaan keuangan Anda
                                </p>
                            </div>

                            {/* Status Alert */}
                            {status && (
                                <Alert className="border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-950">
                                    <AlertDescription className="text-emerald-800 dark:text-emerald-200">
                                        {status}
                                    </AlertDescription>
                                </Alert>
                            )}

                            {/* Email Field */}
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <div className="relative">
                                    <Mail className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        placeholder="nama@email.com"
                                        autoComplete="username"
                                        autoFocus
                                        className="pl-10"
                                        onChange={(e) => setData('email', e.target.value)}
                                    />
                                </div>
                                {errors.email && <InputError message={errors.email} />}
                            </div>

                            {/* Password Field */}
                            <div className="grid gap-2">
                                <div className="flex items-center justify-between">
                                    <Label htmlFor="password">Password</Label>
                                    {canResetPassword && (
                                        <Link
                                            href={route('password.request')}
                                            className="text-sm font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400"
                                        >
                                            Lupa Password?
                                        </Link>
                                    )}
                                </div>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        value={data.password}
                                        placeholder="••••••••"
                                        autoComplete="current-password"
                                        className="pl-10"
                                        onChange={(e) => setData('password', e.target.value)}
                                    />
                                </div>
                                {errors.password && <InputError message={errors.password} />}
                            </div>

                            {/* Remember Me */}
                            <div className="flex items-center space-x-2">
                                <Checkbox
                                    id="remember"
                                    checked={data.remember}
                                    onCheckedChange={(checked) => setData('remember', checked)}
                                />
                                <label
                                    htmlFor="remember"
                                    className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    Ingat saya
                                </label>
                            </div>

                            {/* Submit Button */}
                            <Button
                                type="submit"
                                className="group w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700"
                                disabled={processing}
                            >
                                {processing ? (
                                    'Memproses...'
                                ) : (
                                    <>
                                        Masuk
                                        <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" />
                                    </>
                                )}
                            </Button>

                            {/* Divider */}
                            <div className="relative">
                                <div className="absolute inset-0 flex items-center">
                                    <span className="w-full border-t" />
                                </div>
                                <div className="relative flex justify-center text-xs uppercase">
                                    <span className="bg-background px-2 text-muted-foreground">Atau</span>
                                </div>
                            </div>

                            {/* Register Link */}
                            <div className="text-center text-sm">
                                <span className="text-muted-foreground">Belum punya akun? </span>
                                <Link
                                    href={route('register')}
                                    className="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400"
                                >
                                    Daftar sekarang
                                </Link>
                            </div>
                        </div>
                    </form>

                    {/* Right Side - Image/Illustration */}
                    <div className="relative hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 md:block">
                        <div className="absolute inset-0 bg-grid-white/10" />
                        <div className="relative flex h-full flex-col items-center justify-center p-10 text-white">
                            {/* Decorative Elements */}
                            <div className="absolute right-10 top-10 h-20 w-20 rounded-full bg-white/10 backdrop-blur-sm" />
                            <div className="absolute bottom-20 left-10 h-32 w-32 rounded-full bg-white/10 backdrop-blur-sm" />

                            {/* Content */}
                            <div className="relative z-10 text-center">
                                <div className="mb-6 inline-flex rounded-full bg-white/20 p-4 backdrop-blur-sm">
                                    <Sparkles className="h-12 w-12" />
                                </div>
                                <h2 className="mb-4 text-3xl font-bold">Kelola Keuangan dengan Mudah</h2>
                                <p className="mb-8 text-emerald-50">
                                    Platform terpercaya untuk mengelola keuangan bisnis dan personal Anda
                                </p>
                                <div className="flex flex-col gap-3">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                                            ✓
                                        </div>
                                        <span className="text-sm">Dashboard real-time</span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                                            ✓
                                        </div>
                                        <span className="text-sm">Laporan keuangan otomatis</span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                                            ✓
                                        </div>
                                        <span className="text-sm">Keamanan tingkat enterprise</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* Footer */}
            <div className="text-balance text-center text-xs text-muted-foreground">
                Dengan masuk, Anda menyetujui{' '}
                <Link href="#" className="font-medium hover:text-foreground">
                    Syarat & Ketentuan
                </Link>{' '}
                dan{' '}
                <Link href="#" className="font-medium hover:text-foreground">
                    Kebijakan Privasi
                </Link>
            </div>
        </div>
    );
}

Login.layout = (page) => <GuestLayout title="Login" children={page} />;