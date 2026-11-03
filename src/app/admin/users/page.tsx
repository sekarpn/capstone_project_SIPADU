'use client';

import { useState } from 'react';
import { Search } from 'lucide-react';
import { MOCK_USERS } from '@/lib/mock-data';

export default function AdminUsersPage() {
  const [search, setSearch] = useState('');
  const users = MOCK_USERS.filter(u => u.role === 'user');

  const filtered = users.filter(u =>
    u.name.toLowerCase().includes(search.toLowerCase()) ||
    u.email.toLowerCase().includes(search.toLowerCase()) ||
    u.organization.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Data Pengguna</h1>
        <p className="text-gray-500 text-sm">Daftar pengguna terdaftar</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm p-4">
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5">
            <Search size={14} className="text-gray-400" />
            <input
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder="Cari pengguna..."
              className="text-sm outline-none w-48"
            />
          </div>
          <span className="text-sm text-gray-500">{filtered.length} pengguna</span>
        </div>

        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-gray-200">
              {['No.', 'Nama', 'Email', 'Organisasi', 'Role'].map(h => (
                <th key={h} className="text-left py-3 px-3 text-gray-600 font-semibold text-xs">{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {filtered.map((u, i) => (
              <tr key={u.id} className="border-b border-gray-50 hover:bg-gray-50">
                <td className="py-3 px-3 text-gray-500">{i + 1}.</td>
                <td className="py-3 px-3 font-medium text-gray-800">{u.name}</td>
                <td className="py-3 px-3 text-gray-600">{u.email}</td>
                <td className="py-3 px-3 text-gray-600">{u.organization}</td>
                <td className="py-3 px-3">
                  <span className="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold capitalize">
                    {u.role}
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
