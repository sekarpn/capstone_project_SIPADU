'use client';

import { MOCK_ROOMS } from '@/lib/mock-data';

export default function AdminRoomsPage() {
  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Data Auditorium</h1>
        <p className="text-gray-500 text-sm">Daftar ruang meeting yang tersedia</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-4">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-gray-200">
              {['No.', 'Nama Ruang', 'Kapasitas', 'Lokasi', 'Fasilitas', 'Status'].map(h => (
                <th key={h} className="text-left py-3 px-3 text-gray-600 font-semibold text-xs">{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {MOCK_ROOMS.map((r, i) => (
              <tr key={r.id} className="border-b border-gray-50 hover:bg-gray-50">
                <td className="py-3 px-3 text-gray-500">{i + 1}.</td>
                <td className="py-3 px-3 font-medium text-gray-800">{r.name}</td>
                <td className="py-3 px-3 text-gray-600">{r.capacity} orang</td>
                <td className="py-3 px-3 text-gray-600">{r.location}</td>
                <td className="py-3 px-3 text-gray-500 text-xs">{r.facilities.join(', ')}</td>
                <td className="py-3 px-3">
                  <span className={`px-2 py-1 rounded-full text-xs font-semibold ${r.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                    {r.isActive ? 'Aktif' : 'Nonaktif'}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
