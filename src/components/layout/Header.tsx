'use client';

import { Bell, User } from 'lucide-react';
import { useAuth } from '@/context/AuthContext';

export default function Header() {
  const { user } = useAuth();

  const today = new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });

  return (
    <header className="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-end gap-4">
      <span className="text-sm text-gray-600 mr-auto">{today}</span>
      <div className="relative">
        <button className="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
          <Bell size={18} className="text-gray-600" />
        </button>
        <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[10px] flex items-center justify-center">
          3
        </span>
      </div>
      <div className="flex items-center gap-2">
        <div className="w-9 h-9 rounded-full bg-[#5a7c50] flex items-center justify-center">
          <User size={18} className="text-white" />
        </div>
        {user && (
          <div className="hidden sm:block text-right">
            <p className="text-sm font-semibold text-gray-800 leading-tight">{user.name}</p>
            <p className="text-xs text-gray-500 leading-tight capitalize">{user.role}</p>
          </div>
        )}
      </div>
    </header>
  );
}
