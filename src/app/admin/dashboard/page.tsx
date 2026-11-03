'use client';

import { Building2, Users, CalendarCheck, Clock } from 'lucide-react';
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts';
import StatCard from '@/components/ui/StatCard';
import StatusBadge from '@/components/ui/StatusBadge';
import { MOCK_ROOMS, MOCK_USERS, MOCK_BOOKINGS, CHART_DATA } from '@/lib/mock-data';

export default function AdminDashboard() {
  const pendingCount = MOCK_BOOKINGS.filter(b => b.status === 'pending').length;
  const upcomingBookings = MOCK_BOOKINGS.filter(b => b.status === 'approved').slice(0, 3);

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p className="text-gray-500 text-sm">Overview peminjaman auditorium</p>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard
          title="Auditorium"
          subtitle="Total auditorium saat ini"
          value={MOCK_ROOMS.length}
          icon={<Building2 size={22} />}
        />
        <StatCard
          title="Pengguna"
          subtitle="Total pengguna saat ini"
          value={MOCK_USERS.filter(u => u.role === 'user').length}
          icon={<Users size={22} />}
        />
        <StatCard
          title="Peminjaman"
          subtitle="Total peminjaman bulan ini"
          value={MOCK_BOOKINGS.length}
          icon={<CalendarCheck size={22} />}
        />
        <StatCard
          title="Pending"
          subtitle="Total pending saat ini"
          value={pendingCount}
          icon={<Clock size={22} />}
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div className="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm">
          <h2 className="font-semibold text-gray-800 mb-4">Statistik Peminjaman Ruangan</h2>
          <ResponsiveContainer width="100%" height={200}>
            <BarChart data={CHART_DATA}>
              <XAxis dataKey="month" tick={{ fontSize: 12 }} />
              <YAxis tick={{ fontSize: 12 }} />
              <Tooltip />
              <Bar dataKey="bookings" fill="#5a7c50" radius={[4, 4, 0, 0]} name="Jumlah Peminjaman" />
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="bg-white rounded-xl p-5 shadow-sm">
          <h2 className="font-semibold text-gray-800 mb-4">Peminjaman Mendatang</h2>
          <div className="space-y-3">
            {upcomingBookings.length === 0 && (
              <p className="text-gray-400 text-sm">Tidak ada peminjaman mendatang.</p>
            )}
            {upcomingBookings.map(b => (
              <div key={b.id} className="border border-gray-100 rounded-lg p-3">
                <div className="flex items-start justify-between gap-2">
                  <p className="font-semibold text-gray-800 text-sm">{b.roomName}</p>
                  <StatusBadge status={b.status} />
                </div>
                <p className="text-xs text-gray-500 mt-1">{b.date}</p>
                <p className="text-xs text-gray-500">{b.startTime} – {b.endTime} WIB</p>
                <p className="text-xs text-gray-400 mt-1">{b.organization}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
