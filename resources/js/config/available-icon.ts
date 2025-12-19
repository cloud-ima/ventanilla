import {
    Briefcase,
    Building2,
    Car,
    Coins,
    FileText,
    GraduationCap,
    Heart,
    Home,
    Landmark,
    Shield,
    TreePine,
    Users,
    type LucideIcon,
} from 'lucide-vue-next';

export interface IconOption {
    name: string;
    component: LucideIcon;
    label: string;
}

export const AVAILABLE_ICONS: IconOption[] = [
    { name: 'Building2', component: Building2, label: 'Edificio' },
    { name: 'Car', component: Car, label: 'Vehículo' },
    { name: 'Coins', component: Coins, label: 'Monedas' },
    { name: 'Landmark', component: Landmark, label: 'Gobierno' },
    { name: 'FileText', component: FileText, label: 'Documento' },
    { name: 'Users', component: Users, label: 'Personas' },
    { name: 'Briefcase', component: Briefcase, label: 'Maletín' },
    { name: 'Home', component: Home, label: 'Casa' },
    { name: 'TreePine', component: TreePine, label: 'Árbol' },
    { name: 'Heart', component: Heart, label: 'Salud' },
    { name: 'GraduationCap', component: GraduationCap, label: 'Educación' },
    { name: 'Shield', component: Shield, label: 'Seguridad' },
];

export const getIconComponent = (iconName: string): LucideIcon => {
    const icon = AVAILABLE_ICONS.find((i) => i.name === iconName);
    return icon?.component || FileText;
};
