'use client';

import { MOCK_BOOKINGS } from '@/lib/mock-data';
import StatusBadge from '@/components/ui/StatusBadge';

export default function AdminReportsPage() {
  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Laporan</h1>
        <p className="text-gray-500 text-sm">Rekap data peminjaman ruang</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-4">
        <div className="flex items-center justify-between mb-4">
          <p className="text-sm text-gray-600 font-semibold">Rekap Peminjaman — September 2026</p>
          <button className="text-sm bg-[#5a7c50] text-white px-4 py-1.5 rounded-lg hover:bg-[#4a6840] transition-colors">
            Export
          </button>
        </div>

        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-gray-200">
              {['No.', 'Ruang', 'Peminjam', 'Tanggal', 'Mulai', 'Selesai', 'Keperluan', 'Status'].map(h => (
                <th key={h} className="text-left py-3 px-3 text-gray-600 font-semibold text-xs">{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {MOCK_BOOKINGS.map((b, i) => (
              <tr key={b.id} className="border-b border-gray-50 hover:bg-gray-50">
                <td className="py-3 px-3 text-gray-500">{i + 1}.</td>
                <td className="py-3 px-3 font-medium text-gray-800">{b.roomName}</td>
                <td className="py-3 px-3 text-gray-600">{b.organization}</td>
                <td className="py-3 px-3 text-gray-600">{b.date}</td>
                <td className="py-3 px-3 text-gray-600">{b.startTime}</td>
                <td className="py-3 px-3 text-gray-600">{b.endTime}</td>
                <td className="py-3 px-3 text-gray-500 text-xs">{b.purpose}</td>
                <td className="py-3 px-3"><StatusBadge status={b.status} /></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
