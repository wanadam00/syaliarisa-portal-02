// src/config/nav.ts
import { Home, Info, Users, FileText, Gavel, Wrench, Phone, Network } from "lucide-vue-next";
import { type NavItem } from "@/types";

export const mainNavItems: NavItem[] = [
    { title: "Home", href: route("admin.home-sections.index"), icon: Home },
    { title: "Company Info", href: route("admin.company-info.index"), icon: Info },
    {
        title: "Organization Chart",
        icon: Network,
        children: [
            { title: "Employees", href: route("admin.employees.index") },
            { title: "Positions", href: route("admin.positions.index") },
        ],
    },
    { title: "Standards Applications", href: route("admin.standard-applications.index"), icon: FileText },
    { title: "Legislations", href: route("admin.legislations.index"), icon: Gavel },
    { title: "Services", href: route("admin.services.index"), icon: Wrench },
    { title: "Contact Us", href: route("admin.contact-info.index"), icon: Phone },
];
