'use client';

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useAuth } from '@/context/AuthContext';
import {
  LayoutDashboard, Users, Building2, FileText, CalendarCheck,
  User, LogOut, Search,
} from 'lucide-react';

interface NavItem {
  label: string;
  href: string;
  icon: React.ReactNode;
}

interface NavGroup {
  group?: string;
  items: NavItem[];
}

const ADMIN_NAV: NavGroup[] = [
  {
    items: [
      { label: 'Dashboard', href: '/admin/dashboard', icon: <LayoutDashboard size={18} /> },
    ],
  },
  {
    group: 'MASTER DATA',
    items: [
      { label: 'Data Pengguna', href: '/admin/users', icon: <Users size={18} /> },
      { label: 'Data Auditorium', href: '/admin/rooms', icon: <Building2 size={18} /> },
      { label: 'Laporan', href: '/admin/reports', icon: <FileText size={18} /> },
    ],
  },
  {
    group: 'PEMINJAMAN',
    items: [
      { label: 'Kelola Peminjaman', href: '/admin/bookings', icon: <CalendarCheck size={18} /> },
    ],
  },
  {
    group: 'LAINNYA',
    items: [
      { label: 'Akun', href: '/admin/account', icon: <User size={18} /> },
    ],
  },
];

const USER_NAV: NavGroup[] = [
  {
    items: [
      { label: 'Dashboard', href: '/user/dashboard', icon: <LayoutDashboard size={18} /> },
    ],
  },
  {
    group: 'PEMINJAMAN',
    items: [
      { label: 'Buat Peminjaman', href: '/user/book', icon: <CalendarCheck size={18} /> },
      { label: 'Riwayat Peminjaman', href: '/user/history', icon: <FileText size={18} /> },
    ],
  },
  {
    group: 'LAINNYA',
    items: [
      { label: 'Akun', href: '/user/account', icon: <User size={18} /> },
    ],
  },
];

export default function Sidebar({ role }: { role: 'admin' | 'user' }) {
  const pathname = usePathname();
  const router = useRouter();
  const { logout } = useAuth();
  const nav = role === 'admin' ? ADMIN_NAV : USER_NAV;

  const handleLogout = () => {
    logout();
    router.push('/login');
  };

  return (
    <aside className="w-64 bg-white border-r border-gray-200 flex flex-col h-screen flex-shrink-0">
      <div className="p-4 border-b border-gray-200">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-full bg-[#5a7c50] flex items-center justify-center flex-shrink-0">
            <span className="text-white font-bold text-sm">S</span>
          </div>
          <div>
            <p className="font-bold text-[#5a7c50] text-sm leading-tight">SIPADU</p>
            <p className="text-gray-400 text-[10px] leading-tight">SISTEM PEMINJAMAN RUANG</p>
          </div>
        </div>
      </div>

      <div className="p-3 border-b border-gray-100">
        <div className="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
          <Search size={14} className="text-gray-400" />
          <input
            placeholder="Cari..."
            className="text-sm bg-transparent outline-none text-gray-600 w-full"
          />
        </div>
      </div>

      <nav className="flex-1 overflow-y-auto py-2">
        {nav.map((group, gi) => (
          <div key={gi} className="mb-1">
            {group.group && (
              <p className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-4 py-2">
                {group.group}
              </p>
            )}
            {group.items.map(item => {
              const active = pathname === item.href;
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={`flex items-center gap-3 mx-2 px-3 py-2 rounded-lg text-sm transition-colors ${
                    active
                      ? 'bg-[#5a7c50] text-white font-semibold'
                      : 'text-gray-600 hover:bg-gray-100'
                  }`}
                >
                  <span className={active ? 'text-white' : 'text-gray-400'}>{item.icon}</span>
                  {item.label}
                </Link>
              );
            })}
          </div>
        ))}
      </nav>

      <div className="p-3 border-t border-gray-200">
        <button
          onClick={handleLogout}
          className="w-full flex items-center gap-2 justify-center bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 rounded-lg transition-colors"
        >
          <LogOut size={16} />
          Log Out
        </button>
      </div>
    </aside>
  );
}
