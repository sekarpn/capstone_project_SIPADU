'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/context/AuthContext';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login } = useAuth();
  const router = useRouter();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    const success = login(email, password);
    if (!success) {
      setError('Email atau password salah.');
      return;
    }
    const stored = localStorage.getItem('auth_user');
    const user = stored ? JSON.parse(stored) : null;
    if (user?.role === 'admin') {
      router.push('/admin/dashboard');
    } else {
      router.push('/user/dashboard');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white rounded-2xl shadow-md p-8 w-full max-w-sm">
        <div className="text-center mb-6">
          <div className="w-14 h-14 rounded-full bg-[#5a7c50] flex items-center justify-center mx-auto mb-3">
            <span className="text-white text-2xl font-bold">S</span>
          </div>
          <h1 className="text-2xl font-bold text-[#5a7c50]">SIPADU</h1>
          <p className="text-gray-500 text-sm mt-1">Sistem Peminjaman Ruang Meeting</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              type="email"
              value={email}
              onChange={e => setEmail(e.target.value)}
              placeholder="admin@test.com"
              required
              className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              value={password}
              onChange={e => setPassword(e.target.value)}
              placeholder="••••••••"
              required
              className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a7c50]"
            />
          </div>

          {error && (
            <p className="text-red-500 text-sm">{error}</p>
          )}

          <button
            type="submit"
            className="w-full bg-[#5a7c50] hover:bg-[#4a6840] text-white font-semibold py-2 rounded-lg transition-colors"
          >
            Masuk
          </button>
        </form>

        <div className="mt-5 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
          <p className="font-semibold mb-1">Akun Demo:</p>
          <p>Admin: admin@test.com / admin123</p>
          <p>User: user@test.com / user123</p>
        </div>
      </div>
    </div>
  );
}
