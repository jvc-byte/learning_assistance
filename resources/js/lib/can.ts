import { usePage } from "@inertiajs/vue3";

interface Auth {
    user: {
        permissions: any[]; // Array of permission objects
        roles: string[];
    };
    permissions: string[];
    roles: string[];
}

export function can(permission: string): boolean {
    const auth = usePage().props.auth as Auth;
    console.log('Auth object:', auth);
    console.log('Checking permission:', permission);
    console.log('User permissions:', auth?.permissions);
    return auth?.permissions?.includes(permission) || false;
}