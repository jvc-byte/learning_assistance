<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, BookIcon, Folder, LayoutGrid, MessageSquareTextIcon, Users, UserCog } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { can } from '@/lib/can';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    ...(can('users.super') ? [
        {
            title: 'Users',
            href: '/users',
            icon: Users,
        },
        {
            title: 'Roles',
            href: '/roles',
            icon: UserCog,
        },
    ] : []),
    {
        title: 'Lessons',
        href: '/lessons',
        icon: BookIcon,
    },
    {
        title: 'AI ChatBot',
        href: '/chatbot',
        icon: MessageSquareTextIcon,
    }
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/jvc-byte/learning_assistance',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: '#',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
