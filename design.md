import { FormEvent, ReactNode, useState } from 'react'

type IconName =
  | 'arrow'
  | 'bell'
  | 'building'
  | 'calendar'
  | 'chart'
  | 'check'
  | 'chevron'
  | 'clock'
  | 'document'
  | 'eye'
  | 'grid'
  | 'help'
  | 'lock'
  | 'logo'
  | 'logout'
  | 'menu'
  | 'search'
  | 'shield'
  | 'users'
  | 'warning'
const paths: Record<IconName, ReactNode> = {
  arrow: <><path d="M5 12h14M13 6l6 6-6 6" /></>,
  bell: <><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" /></>,
  building: <><path d="M3 21h18M6 21V5l6-3 6 3v16M9 9h1M14 9h1M9 13h1M14 13h1M9 17h1M14 17h1" /></>,
  calendar: <><rect x="3" y="5" width="18" height="16" rx="2" /><path d="M16 3v4M8 3v4M3 10h18" /></>,
  chart: <><path d="M4 19V9M10 19V5M16 19v-8M22 19H2" /></>,
  check: <><path d="m5 12 4 4L19 6" /></>,
  chevron: <><path d="m9 18 6-6-6-6" /></>,
  clock: <><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></>,
  document: <><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6M8 13h8M8 17h5" /></>,
  eye: <><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" /><circle cx="12" cy="12" r="2.5" /></>,
  grid: <><rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" /><rect x="3" y="14" width="7" height="7" rx="1" /><rect x="14" y="14" width="7" height="7" rx="1" /></>,
  help: <><circle cx="12" cy="12" r="9" /><path d="M9.8 9a2.4 2.4 0 1 1 3.4 2.2c-.8.4-1.2.9-1.2 1.8M12 17h.01" /></>,
  lock: <><rect x="4" y="10" width="16" height="11" rx="2" /><path d="M8 10V7a4 4 0 0 1 8 0v3" /></>,
  logo: <><path d="M12 2 4 5v6c0 5.1 3.4 9.6 8 11 4.6-1.4 8-5.9 8-11V5l-8-3Z" /><path d="M8.5 11.5 11 14l4.5-5" /></>,
  logout: <><path d="M10 17l5-5-5-5M15 12H3M15 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4" /></>,
  menu: <><path d="M4 6h16M4 12h16M4 18h16" /></>,
  search: <><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></>,
  shield: <><path d="M12 2 4 5v6c0 5.1 3.4 9.6 8 11 4.6-1.4 8-5.9 8-11V5l-8-3Z" /></>,
  users: <><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></>,
  warning: <><path d="M10.3 3.8 2.2 18a2 2 0 0 0 1.7 3h16.2a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0Z" /><path d="M12 9v4M12 17h.01" /></>,
}

function Icon({ name, size = 20 }: { name: IconName; size?: number }) {
  return <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">{paths[name]}</svg>
}

const adminNavItems = [
  { icon: 'grid' as IconName, label: 'Dashboard' },
  { icon: 'calendar' as IconName, label: 'Periode Evaluasi' },
  { icon: 'building' as IconName, label: 'Manajemen OPD' },
  { icon: 'document' as IconName, label: 'Indikator & Dokumen' },
  { icon: 'shield' as IconName, label: 'Verifikasi', badge: '12' },
  { icon: 'chart' as IconName, label: 'Laporan & Progres' },
  { icon: 'users' as IconName, label: 'Pengguna' },
]

const opdNavItems = [
  { icon: 'grid' as IconName, label: 'Dashboard OPD' },
  { icon: 'calendar' as IconName, label: 'Periode Evaluasi' },
  { icon: 'document' as IconName, label: 'Indikator Saya', badge: '8' },
  { icon: 'clock' as IconName, label: 'Bukti Dukung' },
  { icon: 'warning' as IconName, label: 'Catatan & Revisi', badge: '3' },
  { icon: 'shield' as IconName, label: 'Riwayat Verifikasi' },
  { icon: 'bell' as IconName, label: 'Notifikasi', badge: '5' },
  { icon: 'chart' as IconName, label: 'Laporan Progres' },
  { icon: 'users' as IconName, label: 'Profil Akun' },
]

function Logo({ compact = false }: { compact?: boolean }) {
  return (
    <div className="brand">
      <span className="brand-mark"><Icon name="logo" size={compact ? 21 : 25} /></span>
      <span><b>SIAGA</b> KLA<small>Sistem Monitoring KLA</small></span>
    </div>
  )
}

function Login({ onLogin }: { onLogin: (role: 'admin' | 'opd') => void }) {
  const [showPassword, setShowPassword] = useState(false)
  const [role, setRole] = useState<'admin' | 'opd'>('admin')
  const submit = (event: FormEvent) => {
    event.preventDefault()
    return onLogin(role)
  }

  return (
    <main className="login-page">
      <section className="login-story">
        <div className="story-inner">
          <Logo />
          <div className="story-copy">
            <span className="eyebrow light">Platform Evaluasi KLA Terintegrasi</span>
            <h1>Wujudkan Kabupaten/Kota <em>Layak Anak</em> bersama.</h1>
            <p>Kelola indikator, bukti dukung, dan proses verifikasi dalam satu sistem yang transparan dan terukur.</p>
            <div className="principles">
              <span><Icon name="check" size={16} /> Satu indikator</span>
              <span><Icon name="check" size={16} /> Satu bukti</span>
              <span><Icon name="check" size={16} /> Tepat waktu</span>
            </div>
          </div>
          <p className="story-footer">Dikelola oleh Pemerintah Kabupaten Sukamaju</p>
        </div>
      </section>
      <section className="login-panel">
        <div className="mobile-logo"><Logo /></div>
        <form className="login-card" onSubmit={submit}>
          <div className="login-heading">
            <span className="eyebrow">Akses Sistem</span>
            <h2>Selamat datang kembali</h2>
            <p>Pilih jenis akun, lalu masuk menggunakan kredensial yang telah diberikan.</p>
          </div>
          <div className="role-switch" aria-label="Pilih jenis akun">
            <button type="button" className={role === 'admin' ? 'active' : ''} onClick={() => setRole('admin')}>
              <Icon name="shield" size={17} /> Admin KLA
            </button>
            <button type="button" className={role === 'opd' ? 'active' : ''} onClick={() => setRole('opd')}>
              <Icon name="building" size={17} /> Operator OPD
            </button>
          </div>
          {role === 'opd' && (
            <label>
              <span>Organisasi Perangkat Daerah</span>
              <div className="field select-field">
                <Icon name="building" size={18} />
                <select defaultValue="dp3a" required>
                  <option value="dp3a">Dinas PPPA</option>
                  <option value="dukcapil">Dinas Kependudukan dan Pencatatan Sipil</option>
                  <option value="kesehatan">Dinas Kesehatan</option>
                  <option value="pendidikan">Dinas Pendidikan</option>
                </select>
              </div>
            </label>
          )}
          <label>
            <span>Alamat email</span>
            <div className="field"><Icon name="users" size={18} /><input key={role} type="email" defaultValue={role === 'admin' ? 'admin@siagakla.go.id' : 'operator.dp3a@siagakla.go.id'} required /></div>
          </label>
          <label>
            <span>Kata sandi</span>
            <div className="field"><Icon name="lock" size={18} /><input type={showPassword ? 'text' : 'password'} defaultValue="siagakla2026" required /><button type="button" className="icon-button" onClick={() => setShowPassword(!showPassword)} aria-label="Tampilkan kata sandi"><Icon name="eye" size={18} /></button></div>
          </label>
          <div className="login-options">
            <label className="checkbox"><input type="checkbox" /> <span>Ingat saya</span></label>
            <button type="button" className="text-button">Lupa kata sandi?</button>
          </div>
          <button className="primary-button" type="submit">Masuk sebagai {role === 'admin' ? 'Admin KLA' : 'Operator OPD'} <Icon name="arrow" size={18} /></button>
          <div className="secure-note"><Icon name="lock" size={15} /> Akses dilindungi dan seluruh aktivitas tercatat.</div>
        </form>
        <div className="support"><Icon name="help" size={18} /> Mengalami kendala? <button type="button">Hubungi administrator</button></div>
      </section>
    </main>
  )
}

const verificationRows = [
  { name: 'Dokumen Kebijakan Pemenuhan Hak Identitas Anak', opd: 'Disdukcapil', cluster: 'Hak Sipil & Kebebasan', time: '10 menit lalu', tone: 'blue' },
  { name: 'Laporan Data Pelayanan Anak Tahun 2026', opd: 'Dinas Kesehatan', cluster: 'Kesehatan Dasar', time: '35 menit lalu', tone: 'blue' },
  { name: 'SK Tim Pelaksana Kabupaten Layak Anak', opd: 'DP3A', cluster: 'Kelembagaan', time: '1 jam lalu', tone: 'amber' },
]
function StatCard({ icon, label, value, note, tone }: { icon: IconName; label: string; value: string; note: string; tone: string }) {
  return <article className="stat-card"><span className={`stat-icon ${tone}`}><Icon name={icon} /></span><div><p>{label}</p><strong>{value}</strong><small>{note}</small></div></article>
}
function ProgressRow({ name, value, color = 'green' }: { name: string; value: number; color?: string }) {
  return <div className="progress-row"><div><span>{name}</span><b>{value}%</b></div><div className="track"><span className={color} style={{ width: `${value}%` }} /></div></div>
}

function Dashboard({ onLogout, role }: { onLogout: () => void; role: 'admin' | 'opd' }) {
  const navItems = role === 'admin' ? adminNavItems : opdNavItems
  const [active, setActive] = useState(role === 'admin' ? 'Dashboard' : 'Dashboard OPD')
  const [menuOpen, setMenuOpen] = useState(false)
  return (
    <div className="app-shell">
      <aside className={menuOpen ? 'sidebar open' : 'sidebar'}>
        <div className="sidebar-top"><Logo compact /><button className="mobile-close" onClick={() => setMenuOpen(false)}>×</button></div>
        <p className="nav-label">{role === 'admin' ? 'Menu administrasi' : 'Menu operator OPD'}</p>
        <nav>{navItems.map(item => <button key={item.label} className={active === item.label ? 'active' : ''} onClick={() => { setActive(item.label); setMenuOpen(false) }}><Icon name={item.icon} size={19} /><span>{item.label}</span>{item.badge && <b>{item.badge}</b>}</button>)}</nav>
        <div className="sidebar-bottom">
          <div className="help-card"><span><Icon name="help" /></span><strong>Butuh bantuan?</strong><p>Pelajari panduan penggunaan SIAGA KLA.</p><button>Pusat Bantuan <Icon name="arrow" size={15} /></button></div>
          <button className="logout" onClick={onLogout}><Icon name="logout" size={18} /> Keluar</button>
        </div>
      </aside>
      <div className="main-area">
        <header>
          <button className="menu-trigger" onClick={() => setMenuOpen(true)}><Icon name="menu" /></button>
          <div className="search"><Icon name="search" size={18} /><input placeholder="Cari indikator, OPD, atau dokumen..." /></div>
          <div className="header-actions">
            <button className="notification"><Icon name="bell" size={20} /><i /></button>
            <div className="profile"><span>{role === 'admin' ? 'AK' : 'SR'}</span><div><b>{role === 'admin' ? 'Andi Kurniawan' : 'Siti Rahma'}</b><small>{role === 'admin' ? 'Admin KLA' : 'Operator Dinas PPPA'}</small></div><Icon name="chevron" size={15} /></div>
          </div>
        </header>
        <main className="content">
          <div className="page-title">
            <div><p>Senin, 10 November 2026</p><h1>Selamat datang, Andi</h1><span>Pantau progres evaluasi dan tindak lanjuti dokumen hari ini.</span></div>
            <label className="period-select"><span>Periode aktif</span><select defaultValue="2026"><option value="2026">Evaluasi KLA 2026</option><option value="2025">Evaluasi KLA 2025</option></select></label>
          </div>
          <section className="stats">
            <StatCard icon="document" label="Total indikator" value="32" note="Dalam 5 cluster aktif" tone="teal" />
            <StatCard icon="clock" label="Menunggu verifikasi" value="12" note="4 dokumen baru hari ini" tone="blue" />
            <StatCard icon="warning" label="Perlu revisi" value="8" note="3 mendekati tenggat" tone="amber" />
            <StatCard icon="check" label="Terverifikasi" value="68%" note="Naik 12% bulan ini" tone="green" />
          </section>
          <section className="dashboard-grid">
            <article className="panel overview-panel">
              <div className="panel-head"><div><h2>Progres Periode 2026</h2><p>Rekap pemenuhan subdokumen wajib</p></div><button className="ghost-button">Lihat laporan <Icon name="arrow" size={15} /></button></div>
              <div className="overview-body">
                <div className="donut"><div><strong>68%</strong><span>Terverifikasi</span></div></div>
                <div className="legend">
                  <div><i className="dot green" /><span><b>108</b> Terverifikasi</span></div>
                  <div><i className="dot blue" /><span><b>24</b> Menunggu verifikasi</span></div>
                  <div><i className="dot amber" /><span><b>16</b> Perlu revisi</span></div>
                  <div><i className="dot gray" /><span><b>12</b> Belum diunggah</span></div>
                </div>
              </div>
            </article>
            <article className="panel deadline-panel">
              <div className="deadline-icon"><Icon name="calendar" /></div>
              <span className="eyebrow">Batas pengumpulan</span><h2>20 hari lagi</h2><p>30 November 2026</p>
              <div className="deadline-track"><span /></div>
              <small>70% periode telah berjalan</small>
            </article>
            <article className="panel cluster-panel">
              <div className="panel-head"><div><h2>Progres per cluster</h2><p>Berdasarkan dokumen terverifikasi</p></div><button className="more">•••</button></div>
              <ProgressRow name="Hak Sipil dan Kebebasan" value={86} />
              <ProgressRow name="Lingkungan Keluarga & Pengasuhan" value={74} color="blue" />
              <ProgressRow name="Kesehatan Dasar & Kesejahteraan" value={68} color="blue" />
              <ProgressRow name="Pendidikan & Kegiatan Budaya" value={55} color="amber" />
              <ProgressRow name="Perlindungan Khusus" value={42} color="amber" />
            </article>
            <article className="panel verification-panel">
              <div className="panel-head"><div><h2>Menunggu verifikasi</h2><p>Dokumen terbaru yang perlu diperiksa</p></div><button className="ghost-button">Lihat semua <Icon name="arrow" size={15} /></button></div>
              <div className="verification-list">
                {verificationRows.map((row, index) => <div className="verification-row" key={row.name}><span className={`file-icon ${row.tone}`}><Icon name="document" size={19} /></span><div className="file-info"><b>{row.name}</b><span>{row.opd} <i /> {row.cluster}</span></div><time>{row.time}</time><button aria-label="Buka dokumen"><Icon name="chevron" size={17} /></button></div>)}
              </div>
            </article>
          </section>
          <footer><span>© 2026 SIAGA KLA Kabupaten Sukamaju</span><span>Versi 1.1 <i /> Kebijakan Privasi</span></footer>
        </main>
      </div>
      {menuOpen && <button className="backdrop" onClick={() => setMenuOpen(false)} aria-label="Tutup menu" />}
    </div>
  )
}

export default function App() {
  const [role, setRole] = useState<'admin' | 'opd' | null>(null)
  return role ? <Dashboard role={role} onLogout={() => setRole(null)} /> : <Login onLogin={setRole} />
}
