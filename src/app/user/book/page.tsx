'use client';

import { useState } from 'react';
import { useAuth } from '@/context/AuthContext';
import { MOCK_ROOMS } from '@/lib/mock-data';

export default function UserBookPage() {
  const { user } = useAuth();
  const [form, setForm] = useState({ roomId: '', date: '', startTime: '', endTime: '', purpose: '' });
  const [submitted, setSubmitted] = useState(false);

  const activeRooms = MOCK_ROOMS.filter(r => r.isActive);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
  };

  if (submitted) {
    return (
      <div className="flex items-center justify-center min-h-[60vh]">
        <div className="bg-white rounded-2xl shadow-sm p-8 text-center max-w-sm w-full">
          <div className="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
            <span className="text-green-600 text-2xl">✓</span>
          </div>
          <h2 className="text-xl font-bold text-gray-800 mb-2">Pengajuan Terkirim!</h2>
          <p className="text-gray-500 text-sm mb-6">Peminjaman Anda sedang menunggu persetujuan admin.</p>
          <button
            onClick={() => { setSubmitted(false); setForm({ roomId: '', date: '', startTime: '', endTime: '', purpose: '' }); }}
            className="w-full bg-[#5a7c50] text-white py-2 rounded-lg font-semibold hover:bg-[#4a6840] transition-colors"
          >
            Buat Peminjaman Baru
          </button>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Buat Peminjaman</h1>
        <p className="text-gray-500 text-sm">Ajukan peminjaman ruang meeting</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-6 max-w-lg">
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Peminjam</label>
            <input
              value={user?.name || ''}
              disabled
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Organisasi</label>
            <input
              value={user?.organization || ''}
              disabled
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Pilih Ruang <span className="text-red-500">*</span></label>
            <select
              name="roomId"
              value={form.roomId}
              onChange={handleChange}
              required
              className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
            >
              <option value="">-- Pilih Ruang --</option>
              {activeRooms.map(r => (
                <option key={r.id} value={r.id}>{r.name} (kapasitas {r.capacity})</option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Tanggal <span className="text-red-500">*</span></label>
            <input
              type="date"
              name="date"
              value={form.date}
              onChange={handleChange}
              required
              className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
            />
          </div>
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span className="text-red-500">*</span></label>
              <input
                type="time"
                name="startTime"
                value={form.startTime}
                onChange={handleChange}
                required
                className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span className="text-red-500">*</span></label>
              <input
                type="time"
                name="endTime"
                value={form.endTime}
                onChange={handleChange}
                required
                className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
              />
            </div>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Keperluan <span className="text-red-500">*</span></label>
            <textarea
              name="purpose"
              value={form.purpose}
              onChange={handleChange}
              required
              rows={3}
              placeholder="Tuliskan keperluan peminjaman..."
              className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50] resize-none"
            />
          </div>
          <button
            type="submit"
            className="w-full bg-[#5a7c50] hover:bg-[#4a6840] text-white font-semibold py-2 rounded-lg transition-colors"
          >
            Ajukan Peminjaman
          </button>
        </form>
      </div>
    </div>
  );
}
