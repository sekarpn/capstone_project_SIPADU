'use client';

import { useAuth } from '@/context/AuthContext';
import { MOCK_ROOMS, MOCK_BOOKINGS } from '@/lib/mock-data';
import StatusBadge from '@/components/ui/StatusBadge';
import { Users, Building2 } from 'lucide-react';

export default function UserDashboard() {
  const { user } = useAuth();
  const myBookings = MOCK_BOOKINGS.filter(b => b.userId === user?.id).slice(0, 3);
  const activeRooms = MOCK_ROOMS.filter(r => r.isActive);

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Selamat Datang, {user?.name}!</h1>
        <p className="text-gray-500 text-sm">Sistem Peminjaman Ruang Meeting</p>
      </div>

      <div className="grid grid-cols-2 gap-4 mb-6">
        <div className="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center">
            <Building2 size={22} className="text-teal-600" />
          </div>
          <div>
            <p className="text-2xl font-bold text-gray-800">{activeRooms.length}</p>
            <p className="text-sm text-gray-500">Ruang Tersedia</p>
          </div>
        </div>
        <div className="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center">
            <Users size={22} className="text-teal-600" />
          </div>
          <div>
            <p className="text-2xl font-bold text-gray-800">{MOCK_BOOKINGS.filter(b => b.userId === user?.id).length}</p>
            <p className="text-sm text-gray-500">Total Peminjaman Saya</p>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div className="bg-white rounded-xl shadow-sm p-5">
          <h2 className="font-semibold text-gray-800 mb-4">Ruang Tersedia</h2>
          <div className="space-y-2">
            {activeRooms.map(r => (
              <div key={r.id} className="flex items-center justify-between border border-gray-100 rounded-lg p-3 hover:bg-gray-50">
                <div>
                  <p className="font-medium text-gray-800 text-sm">{r.name}</p>
                  <p className="text-xs text-gray-500">{r.location} · {r.capacity} orang</p>
                </div>
                <span className="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-semibold">Aktif</span>
              </div>
            ))}
          </div>
        </div>

        <div className="bg-white rounded-xl shadow-sm p-5">
          <h2 className="font-semibold text-gray-800 mb-4">Peminjaman Terbaru Saya</h2>
          <div className="space-y-2">
            {myBookings.length === 0 && (
              <p className="text-gray-400 text-sm">Belum ada peminjaman.</p>
            )}
            {myBookings.map(b => (
              <div key={b.id} className="border border-gray-100 rounded-lg p-3">
                <div className="flex items-center justify-between">
                  <p className="font-medium text-gray-800 text-sm">{b.roomName}</p>
                  <StatusBadge status={b.status} />
                </div>
                <p className="text-xs text-gray-500 mt-1">{b.date} · {b.startTime}–{b.endTime} WIB</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
