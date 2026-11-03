import { ReactNode } from 'react';

interface StatCardProps {
  title: string;
  subtitle: string;
  value: number | string;
  icon: ReactNode;
}

export default function StatCard({ title, subtitle, value, icon }: StatCardProps) {
  return (
    <div className="bg-white rounded-xl p-5 flex items-start justify-between shadow-sm">
      <div>
        <p className="font-semibold text-gray-800 text-sm">{title}</p>
        <p className="text-gray-400 text-xs mt-0.5">{subtitle}</p>
        <p className="text-3xl font-bold text-gray-800 mt-3">{value}</p>
      </div>
      <div className="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center flex-shrink-0">
        <span className="text-teal-600">{icon}</span>
      </div>
    </div>
  );
}
