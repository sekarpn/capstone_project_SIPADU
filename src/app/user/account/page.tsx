'use client';

import { useAuth } from '@/context/AuthContext';
import { User } from 'lucide-react';

export default function UserAccountPage() {
  const { user } = useAuth();

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Akun</h1>
        <p className="text-gray-500 text-sm">Informasi akun Anda</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-6 max-w-md">
        <div className="flex items-center gap-4 mb-6">
          <div className="w-16 h-16 rounded-full bg-[#5a7c50] flex items-center justify-center">
            <User size={28} className="text-white" />
          </div>
          <div>
            <p className="text-xl font-bold text-gray-800">{user?.name}</p>
            <span className="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-semibold">{user?.organization}</span>
          </div>
        </div>
        <div className="space-y-4">
          {[
            { label: 'Email', value: user?.email },
            { label: 'Organisasi', value: user?.organization },
            { label: 'Role', value: user?.role },
          ].map(item => (
            <div key={item.label} className="border-b border-gray-100 pb-3">
              <p className="text-xs text-gray-400 font-medium mb-1">{item.label}</p>
              <p className="text-sm text-gray-800 font-medium capitalize">{item.value}</p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
