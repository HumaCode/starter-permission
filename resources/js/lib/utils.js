import { router } from '@inertiajs/react';
import { clsx } from 'clsx';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';
import { toast } from 'sonner';
import { twMerge } from 'tailwind-merge';

function cn(...inputs) {
    return twMerge(clsx(inputs));
}

function flashMessage(params) {
    return params.props.flash_message;
}

const deleteAction = (url, { closeModal, ...options } = {}) => {
    const defaultOptions = {
        preserveScroll: true,
        preserveState: true,

        onSuccess: (success) => {
            const flash = flashMessage(success);
            if (flash) {
                toast[flash.type](flash.message);
            }

            if (closeModal && typeof closeModal === 'function') {
                closeModal();
            }
        },
        ...options,
    };

    router.delete(url, defaultOptions);
};

const formatDateIndo = (dateString) => {
    if (!dateString) return '-';

    return format(parseISO(dateString), 'eeee, dd MMMM yyyy', {
        locale: id,
    });
};

const formatToRupiah = (amount) => {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    return formatter.format(amount);
};

const STATUSENUM = {
    ACTIVE: 'Aktif',
    INACTIVE: 'Tidak Aktif',
};

const STATUSENUMVARIANT = {
    [STATUSENUM.ACTIVE]: 'emerald',
    [STATUSENUM.INACTIVE]: 'red',
};

const messages = {
    503: {
        title: 'Service Unvailable',
        description: 'Sory, we are doing some maintenance. Please check back soon.',
        status: 503,
    },

    500: {
        title: 'Server Error',
        description: 'Oops, something went wrong.',
        status: 500,
    },

    404: {
        title: 'Not Found',
        description: 'Sorry, the page you are looking for could not be found.',
        status: 404,
    },

    403: {
        title: 'Forbidden',
        description: 'Oops, you are forbidden from accessing this page.',
        status: 403,
    },

    401: {
        title: 'Unauthorized',
        description: 'Oops, you are unauthorized to access this page.',
        status: 401,
    },

    429: {
        title: 'To Many Request',
        description: 'Please try again in just a second.',
        status: 429,
    },
};

const MONTHTYPE = {
    JANUARI: 'januari',
    FEBRUARI: 'februari',
    MARET: 'maret',
    APRIL: 'april',
    MEI: 'mei',
    JUNI: 'juni',
    JULI: 'juli',
    AGUSTUS: 'agustus',
    SEPTEMBER: 'september',
    OKTOBER: 'oktober',
    NOVEMBER: 'november',
    DESEMBER: 'desember',
};

const MONTHTYPEVARIANT = {
    [MONTHTYPE.JANUARI]: 'fuchsia',
    [MONTHTYPE.FEBRUARI]: 'orange',
    [MONTHTYPE.MARET]: 'emerald',
    [MONTHTYPE.APRIL]: 'sky',
    [MONTHTYPE.MEI]: 'purple',
    [MONTHTYPE.JUNI]: 'rose',
    [MONTHTYPE.JULI]: 'pink',
    [MONTHTYPE.AGUSTUS]: 'red',
    [MONTHTYPE.SEPTEMBER]: 'violet',
    [MONTHTYPE.OKTOBER]: 'blue',
    [MONTHTYPE.NOVEMBER]: 'lime',
    [MONTHTYPE.DESEMBER]: 'teal',
};

export {
    cn,
    deleteAction,
    flashMessage,
    formatDateIndo,
    formatToRupiah,
    messages,
    MONTHTYPE,
    MONTHTYPEVARIANT,
    STATUSENUM,
    STATUSENUMVARIANT,
};
