'use client';

import { useState } from 'react';
import { useAuth } from '@/context/AuthContext';
import { MOCK_BOOKINGS } from '@/lib/mock-data';
import { Booking } from '@/types';
import StatusBadge from '@/components/ui/StatusBadge';

export default function UserHistoryPage() {
  const { user } = useAuth();
  const initial = MOCK_BOOKINGS.filter(b => b.userId === user?.id);
  const [bookings, setBookings] = useState<Booking[]>(initial);

  const cancel = (id: string) => {
    setBookings(prev => prev.map(b => b.id === id ? { ...b, status: 'cancelled' } : b));
  };

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Riwayat Peminjaman</h1>
        <p className="text-gray-500 text-sm">Daftar peminjaman yang pernah Anda ajukan</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-4">
        {bookings.length === 0 && (
          <p className="text-center py-8 text-gray-400 text-sm">Belum ada riwayat peminjaman.</p>
        )}
        <div className="space-y-3">
          {bookings.map(b => (
            <div key={b.id} className="border border-gray-100 rounded-xl p-4 hover:bg-gray-50">
              <div className="flex items-start justify-between gap-3">
                <div className="flex-1">
                  <p className="font-semibold text-gray-800">{b.roomName}</p>
                  <p className="text-sm text-gray-500 mt-0.5">{b.purpose}</p>
                  <div className="flex items-center gap-3 mt-2 text-xs text-gray-500">
                    <span>📅 {b.date}</span>
                    <span>🕐 {b.startTime} – {b.endTime} WIB</span>
                  </div>
                </div>
                <div className="flex flex-col items-end gap-2">
                  <StatusBadge status={b.status} />
                  {b.status === 'pending' && (
                    <button
                      onClick={() => cancel(b.id)}
                      className="text-xs text-red-500 hover:text-red-700 underline"
                    >
                      Batalkan
                    </button>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
