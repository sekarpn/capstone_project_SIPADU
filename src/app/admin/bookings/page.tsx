'use client';

import { useState } from 'react';
import { CalendarCheck, Clock, CheckCircle, XCircle, Eye, Check, X, Search } from 'lucide-react';
import { Booking, BookingStatus } from '@/types';
import { MOCK_BOOKINGS } from '@/lib/mock-data';
import StatCard from '@/components/ui/StatCard';
import StatusBadge from '@/components/ui/StatusBadge';

type FilterTab = 'all' | BookingStatus;

export default function AdminBookingsPage() {
  const [bookings, setBookings] = useState<Booking[]>(MOCK_BOOKINGS);
  const [activeTab, setActiveTab] = useState<FilterTab>('all');
  const [search, setSearch] = useState('');
  const [perPage, setPerPage] = useState(5);
  const [page, setPage] = useState(1);

  const updateStatus = (id: string, status: BookingStatus) => {
    setBookings(prev => prev.map(b => b.id === id ? { ...b, status } : b));
  };

  const tabs: { label: string; value: FilterTab }[] = [
    { label: 'Semua', value: 'all' },
    { label: 'Pending', value: 'pending' },
    { label: 'Disetujui', value: 'approved' },
    { label: 'Ditolak', value: 'rejected' },
  ];

  const filtered = bookings.filter(b => {
    const matchTab = activeTab === 'all' || b.status === activeTab;
    const matchSearch = search === '' ||
      b.roomName.toLowerCase().includes(search.toLowerCase()) ||
      b.organization.toLowerCase().includes(search.toLowerCase());
    return matchTab && matchSearch;
  });

  const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
  const paginated = filtered.slice((page - 1) * perPage, page * perPage);

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Kelola Peminjaman</h1>
        <p className="text-gray-500 text-sm">Kelola pengajuan peminjaman auditorium</p>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard title="Peminjaman" subtitle="Total Keseluruhan" value={bookings.length} icon={<CalendarCheck size={22} />} />
        <StatCard title="Pending" subtitle="Total Pending" value={bookings.filter(b => b.status === 'pending').length} icon={<Clock size={22} />} />
        <StatCard title="Disetujui" subtitle="Total Disetujui" value={bookings.filter(b => b.status === 'approved').length} icon={<CheckCircle size={22} />} />
        <StatCard title="Ditolak" subtitle="Total Ditolak" value={bookings.filter(b => b.status === 'rejected').length} icon={<XCircle size={22} />} />
      </div>

      <div className="flex gap-2 mb-4">
        {tabs.map(t => (
          <button
            key={t.value}
            onClick={() => { setActiveTab(t.value); setPage(1); }}
            className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
              activeTab === t.value
                ? 'bg-gray-800 text-white'
                : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
            }`}
          >
            {t.label}
          </button>
        ))}
      </div>

      <div className="bg-white rounded-xl shadow-sm p-4">
        <div className="flex items-center justify-between mb-4 gap-3 flex-wrap">
          <div className="flex items-center gap-2">
            <span className="text-sm text-gray-600">Tampilkan</span>
            <select
              value={perPage}
              onChange={e => { setPerPage(Number(e.target.value)); setPage(1); }}
              className="border border-gray-200 rounded-lg px-2 py-1 text-sm"
            >
              {[5, 10, 25].map(n => <option key={n}>{n}</option>)}
            </select>
            <span className="text-sm text-gray-600">entri</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5">
              <Search size={14} className="text-gray-400" />
              <input
                value={search}
                onChange={e => { setSearch(e.target.value); setPage(1); }}
                placeholder="Cari..."
                className="text-sm outline-none w-40"
              />
            </div>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-gray-200">
                {['No.', 'Auditorium', 'Peminjam', 'Tanggal', 'Waktu Mulai', 'Waktu Selesai', 'Status', 'Aksi'].map(h => (
                  <th key={h} className="text-left py-3 px-3 text-gray-600 font-semibold text-xs">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {paginated.length === 0 && (
                <tr>
                  <td colSpan={8} className="text-center py-8 text-gray-400">Tidak ada data</td>
                </tr>
              )}
              {paginated.map((b, i) => (
                <tr key={b.id} className="border-b border-gray-50 hover:bg-gray-50">
                  <td className="py-3 px-3 text-gray-500">{(page - 1) * perPage + i + 1}.</td>
                  <td className="py-3 px-3 font-medium text-gray-800">{b.roomName}</td>
                  <td className="py-3 px-3 text-gray-600">{b.organization}</td>
                  <td className="py-3 px-3 text-gray-600">{b.date}</td>
                  <td className="py-3 px-3 text-gray-600">{b.startTime}</td>
                  <td className="py-3 px-3 text-gray-600">{b.endTime}</td>
                  <td className="py-3 px-3"><StatusBadge status={b.status} /></td>
                  <td className="py-3 px-3">
                    <div className="flex items-center gap-1">
                      <button
                        onClick={() => updateStatus(b.id, 'approved')}
                        title="Setujui"
                        className="w-7 h-7 rounded border border-gray-200 flex items-center justify-center hover:bg-green-50 hover:border-green-300 transition-colors"
                      >
                        <Check size={14} className="text-green-600" />
                      </button>
                      <button
                        onClick={() => updateStatus(b.id, 'rejected')}
                        title="Tolak"
                        className="w-7 h-7 rounded border border-gray-200 flex items-center justify-center hover:bg-red-50 hover:border-red-300 transition-colors"
                      >
                        <X size={14} className="text-red-500" />
                      </button>
                      <button
                        title="Detail"
                        className="w-7 h-7 rounded bg-[#5a7c50] flex items-center justify-center hover:bg-[#4a6840] transition-colors"
                      >
                        <Eye size={14} className="text-white" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
          <p className="text-xs text-gray-500">
            Menampilkan {filtered.length === 0 ? 0 : (page - 1) * perPage + 1} hingga {Math.min(page * perPage, filtered.length)} dari {filtered.length} entri
          </p>
          <div className="flex items-center gap-1">
            <button
              onClick={() => setPage(p => Math.max(1, p - 1))}
              disabled={page === 1}
              className="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-gray-500 disabled:opacity-40 hover:bg-gray-50"
            >
              ←
            </button>
            {Array.from({ length: totalPages }, (_, i) => i + 1).map(n => (
              <button
                key={n}
                onClick={() => setPage(n)}
                className={`w-8 h-8 rounded border text-sm font-medium transition-colors ${
                  n === page ? 'bg-[#5a7c50] text-white border-[#5a7c50]' : 'border-gray-200 text-gray-600 hover:bg-gray-50'
                }`}
              >
                {n}
              </button>
            ))}
            <button
              onClick={() => setPage(p => Math.min(totalPages, p + 1))}
              disabled={page === totalPages}
              className="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-gray-500 disabled:opacity-40 hover:bg-gray-50"
            >
              →
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
