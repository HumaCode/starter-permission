import * as Icons from '@tabler/icons-react';

export default function DynamicIcon({ name, className = "size-4", fallback = "IconCircle" }) {
    // Get icon component from tabler icons
    const Icon = Icons[name] || Icons[fallback];

    return <Icon className={className} />;
}