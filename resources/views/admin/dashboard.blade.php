@extends('layouts.app')

@section('title', 'IoT Incubation Dashboard - Smart Palao (Owner Only)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" id="incubation-dashboard">

    <!-- Top Header & Status Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-[#22201D] p-4 sm:p-6 rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-3.5 sm:gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#FFF8E1] dark:bg-[#2A261A] flex items-center justify-center p-1 border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm shrink-0">
                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">Technical Incubation Dashboard</h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold uppercase bg-[#FFC107] text-[#1F1F1F] shrink-0">
                        Owner Level
                    </span>
                </div>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                    Chamber: <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->name }}</span> ({{ $chamber->location }}) • Telemetry: <span class="font-mono text-emerald-600 font-bold">ONLINE</span>
                </p>
            </div>
        </div>

        <!-- Telemetry Simulator & Live Actions -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full md:w-auto">
            <!-- Audio Alert Toggle -->
            <button id="toggle-audio-btn" onclick="toggleAudioAlert()" class="px-2.5 sm:px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] bg-[#F4F1EA] dark:bg-[#1A1917] hover:bg-[#FAF8F5] dark:hover:bg-[#262420] transition flex items-center justify-center gap-1.5 shadow-sm" title="Toggle audible alarm for thermal runaway">
                <span id="audio-icon">🔔</span>
                <span id="audio-label" class="truncate">Alarm: ON</span>
            </button>

            <!-- Live Polling Toggle -->
            <button id="toggle-stream-btn" onclick="toggleStream()" class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold bg-[#89B4C4] text-white hover:bg-[#6496A9] transition flex items-center justify-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                <span id="stream-btn-label" class="truncate">Stream: Active</span>
            </button>

            <!-- Inject Normal ESP32 Packet -->
            <button onclick="triggerSimulation(false)" class="px-2.5 sm:px-3 py-2 rounded-xl text-xs font-bold bg-[#FAF8F5] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition flex items-center justify-center gap-1">
                <span>🔄 Packet</span>
            </button>

            <!-- Inject Thermal Runaway Spike -->
            <button onclick="triggerSimulation(true)" class="px-2.5 sm:px-3 py-2 rounded-xl text-xs font-bold bg-[#E63946] text-white hover:bg-red-700 transition flex items-center justify-center gap-1 shadow-sm" title="Simulate chamber thermal spike above critical ceiling to test safety interlocks">
                <span>🔥 Spike</span>
            </button>
        </div>
    </div>

    <!-- Alert System: Thermal Runaway Push Notification Banner -->
    <div id="runaway-banner" class="hidden p-5 rounded-3xl bg-[#FDE8EA] dark:bg-[#2D1619] border-2 border-[#E63946] text-[#3A3A3A] dark:text-[#F2EFE9] shadow-lg transition-all duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#E63946] text-white flex items-center justify-center text-2xl font-black shrink-0 animate-bounce">
                    ⚠️
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-[#E63946] text-white">
                            CRITICAL SAFETY INTERLOCK
                        </span>
                        <h3 class="text-lg font-black text-[#E63946]">Thermal Runaway Detected!</h3>
                    </div>
                    <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] mt-1 leading-relaxed">
                        Chamber or thermal matrix temperature has breached the safety ceiling of <span class="font-bold text-[#E63946] setpoint-critical">{{ $chamber->critical_temp }}°C</span>.
                        Autonomous protective interlock has automatically killed the heater and forced exhaust PWM fan to 100%.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button onclick="emergencyCooldown()" class="px-4 py-2.5 rounded-xl bg-[#E63946] text-white font-bold text-xs hover:bg-red-700 transition shadow">
                    ❄️ Force Emergency Exhaust
                </button>
                <button onclick="dismissAlert()" class="px-3 py-2.5 rounded-xl border border-red-300 dark:border-red-800 bg-white dark:bg-[#1C1B19] text-xs font-bold text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition">
                    Acknowledge
                </button>
            </div>
        </div>
    </div>

    <!-- Ambient Metrics Row (Sensirion SHT31) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Ambient Chamber Temperature -->
        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] mb-2">
                <span>Chamber Ambient (SHT31)</span>
                <span class="px-2 py-0.5 rounded bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4] text-[10px]">Dual Core</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span id="metric-ambient-temp" class="text-4xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">
                    {{ number_format($latestReading->ambient_temp ?? $chamber->setpoint, 2) }}
                </span>
                <span class="text-xl font-bold text-[#7A7A7A] dark:text-[#8E8B83]">°C</span>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-3 border-t border-[#D9CBB8]/40 dark:border-[#3A3833]">
                <span class="text-[#7A7A7A] dark:text-[#8E8B83]">Target Setpoint:</span>
                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9] font-mono setpoint-display">{{ number_format($chamber->setpoint, 2) }}°C</span>
            </div>
            <div class="mt-1 flex items-center justify-between text-xs">
                <span class="text-[#7A7A7A] dark:text-[#8E8B83]">Thermal Variance:</span>
                <span id="temp-delta-badge" class="font-bold font-mono px-1.5 py-0.2 rounded text-[11px] bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300">
                    +0.00°C
                </span>
            </div>
        </div>

        <!-- Relative Humidity -->
        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] mb-2">
                <span>Relative Humidity (SHT31)</span>
                <span class="px-2 py-0.5 rounded bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4] text-[10px]">Membrane Safe</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span id="metric-humidity" class="text-4xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">
                    {{ number_format($latestReading->humidity ?? 65.0, 1) }}
                </span>
                <span class="text-xl font-bold text-[#7A7A7A] dark:text-[#8E8B83]">% RH</span>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-3 border-t border-[#D9CBB8]/40 dark:border-[#3A3833]">
                <span class="text-[#7A7A7A] dark:text-[#8E8B83]">Optimal Range:</span>
                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">60% - 70% RH</span>
            </div>
            <div class="mt-1 flex items-center justify-between text-xs">
                <span class="text-[#7A7A7A] dark:text-[#8E8B83]">Moisture Status:</span>
                <span class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Pore Equilibrium</span>
                </span>
            </div>
        </div>

        <!-- Incubation Stage & Batch Lifecycle -->
        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] mb-2">
                <span>Incubation Progress</span>
                <span class="px-2 py-0.5 rounded bg-[#FFC107]/30 text-[#1F1F1F] font-bold text-[10px]">
                    Day {{ $chamber->batch_day }}/28
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">
                    {{ $chamber->batch_day <= 25 ? 'Setter Phase' : 'Hatcher Phase' }}
                </span>
            </div>
            <div class="w-full bg-[#EBE5D8] dark:bg-[#33302B] h-2.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-[#FFC107] h-full rounded-full transition-all duration-500" style="width: {{ min(100, round(($chamber->batch_day / 28) * 100)) }}%;"></div>
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] text-[#7A7A7A] dark:text-[#8E8B83]">
                <span>Day 1 (Set)</span>
                <span>Day 14 (Candling)</span>
                <span>Day 28 (Pip)</span>
            </div>
        </div>

        <!-- Actuator State / PID Duty -->
        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] mb-2">
                <span>Actuators (PID)</span>
                <span id="pid-mode-badge" class="px-2 py-0.5 rounded font-extrabold uppercase text-[10px] {{ $chamber->mode === 'AUTO' ? 'bg-[#FFC107] text-[#1F1F1F]' : 'bg-purple-100 dark:bg-purple-950/60 text-purple-800 dark:text-purple-300' }}">
                    {{ $chamber->mode }} PID
                </span>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-1">
                <div class="p-2.5 rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="text-[10px] font-bold uppercase text-[#7A7A7A] dark:text-[#8E8B83] block">Heating Element</span>
                    <span id="heater-status-text" class="text-sm font-black font-mono {{ $chamber->heater_state ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">
                        {{ $chamber->heater_state ? '🔥 ACTIVE (ON)' : '⚪ STANDBY (OFF)' }}
                    </span>
                </div>
                <div class="p-2.5 rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="text-[10px] font-bold uppercase text-[#7A7A7A] dark:text-[#8E8B83] block">Exhaust Fan PWM</span>
                    <span id="fan-status-text" class="text-sm font-black font-mono text-[#89B4C4]">
                        🌀 {{ $chamber->fan_pwm }}%
                    </span>
                </div>
            </div>
            <div class="mt-2 text-[11px] text-[#7A7A7A] dark:text-[#8E8B83] flex items-center justify-between">
                <span>Last Telemetry Sync:</span>
                <span id="last-sync-time" class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Live ESP32-CAM Optical Camera Monitor & Control Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: ESP32-CAM Live Incubator Optical Monitor (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="egg-card p-4 sm:p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
                <!-- Camera Header & Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9] flex items-center gap-2">
                                <span>📹</span>
                                <span>ESP32-CAM Live Incubator Camera</span>
                            </h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>1080p Stream</span>
                            </span>
                        </div>
                        <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">Real-time optical feed of incubator chamber & egg clutch (Roxas Main Hatchery)</p>
                    </div>

                    <!-- Lighting & Camera Actions -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="inline-flex rounded-xl p-1 bg-[#F4F1EA] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                            <button type="button" id="btn-mode-standard" onclick="setLightingMode('standard')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white dark:bg-[#2D2B27] text-[#3A3A3A] dark:text-[#F2EFE9] shadow-sm transition flex items-center gap-1">
                                <span>💡</span>
                                <span>Daylight LED</span>
                            </button>
                            <button type="button" id="btn-mode-candling" onclick="setLightingMode('candling')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] hover:text-[#3A3A3A] dark:hover:text-[#F2EFE9] transition flex items-center gap-1">
                                <span>🕯️</span>
                                <span>Candling Glow</span>
                            </button>
                        </div>
                        <button type="button" onclick="cycleZoom()" id="btn-zoom" class="px-2.5 py-1.5 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] text-[11px] font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition" title="Digital Zoom">
                            1.0x
                        </button>
                        <button type="button" onclick="takeCameraSnapshot()" class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition" title="Capture Snapshot Frame">
                            📸
                        </button>
                    </div>
                </div>

                <!-- Camera Viewport (Realistic Incubator Video Monitor) -->
                <div class="mt-4 sm:mt-6 flex flex-col items-center">
                    <div class="relative w-full rounded-2xl overflow-hidden border-2 border-[#3A3A3A] dark:border-[#4A4843] shadow-lg bg-[#141312]">
                        <!-- Top Camera Overlay HUD -->
                        <div class="absolute top-0 inset-x-0 z-20 px-3 py-2 bg-gradient-to-b from-black/85 via-black/40 to-transparent flex items-center justify-between text-[11px] font-mono text-white/90 pointer-events-none">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                <span class="font-bold tracking-wider text-red-400">REC</span>
                                <span class="text-white/60">•</span>
                                <span class="font-bold">ESP32-CAM-ROXAS-01</span>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] text-white/70">
                                <span class="hidden sm:inline">1920x1080 @ 30 FPS</span>
                                <span>RTSP/MJPEG</span>
                                <span id="camera-hud-clock" class="text-amber-400 font-bold">10:00:00</span>
                            </div>
                        </div>

                        <!-- Chamber Viewport Container -->
                        <div id="camera-viewport-bg" class="relative w-full min-h-[300px] sm:min-h-[360px] flex items-center justify-center p-6 transition-all duration-500 bg-gradient-to-b from-[#252320] via-[#1B1917] to-[#11100F] overflow-hidden">
                            <!-- Zoomable Video Canvas Layer -->
                            <div id="camera-zoom-layer" class="relative w-full max-w-lg transition-transform duration-300">
                                <!-- Incubator Chamber Turning Rollers Bed -->
                                <div class="relative w-full rounded-2xl p-6 border border-white/10 shadow-2xl bg-black/40 backdrop-blur-xs">
                                    <!-- Metallic Roller Rods -->
                                    <div class="absolute inset-x-4 inset-y-6 flex flex-col justify-between pointer-events-none opacity-30">
                                        <div class="h-1.5 bg-gradient-to-r from-gray-500 via-gray-300 to-gray-600 rounded-full shadow"></div>
                                        <div class="h-1.5 bg-gradient-to-r from-gray-500 via-gray-300 to-gray-600 rounded-full shadow"></div>
                                        <div class="h-1.5 bg-gradient-to-r from-gray-500 via-gray-300 to-gray-600 rounded-full shadow"></div>
                                        <div class="h-1.5 bg-gradient-to-r from-gray-500 via-gray-300 to-gray-600 rounded-full shadow"></div>
                                        <div class="h-1.5 bg-gradient-to-r from-gray-500 via-gray-300 to-gray-600 rounded-full shadow"></div>
                                    </div>

                                    <!-- Natural Clutch of Duck Eggs in Incubator -->
                                    <div id="egg-clutch-display" class="relative z-10 flex flex-wrap items-center justify-center gap-3 sm:gap-4 py-3">
                                        <!-- Realistic eggs populated dynamically by JS matching current lighting mode -->
                                    </div>

                                    <!-- Subtle Optics Crosshair Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                                        <div class="w-8 h-8 border border-white/50 rounded-full"></div>
                                        <div class="absolute w-12 h-px bg-white/40"></div>
                                        <div class="absolute h-12 w-px bg-white/40"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Camera Status Bar HUD -->
                        <div class="px-3 py-2 bg-black/85 border-t border-white/10 flex flex-wrap items-center justify-between text-[10px] sm:text-[11px] font-mono text-white/75">
                            <div class="flex items-center gap-2">
                                <span id="hud-lighting-label" class="text-amber-400 font-bold">● DAYLIGHT INSPECTION LED (5500K)</span>
                                <span>•</span>
                                <span>ROLLER TILT: +45°</span>
                            </div>
                            <div class="flex items-center gap-2 text-white/60">
                                <span>SHT31: <strong class="text-emerald-400 font-mono">{{ number_format($latestReading->ambient_temp ?? 37.5, 2) }}°C</strong> • <strong class="text-cyan-400 font-mono">{{ number_format($latestReading->humidity ?? 65.0, 1) }}% RH</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Chamber Batch & Inspection Summary Card -->
                    <div class="w-full mt-3 sm:mt-4 p-4 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->batch_name ?? 'BATCH-2026-D01' }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                    {{ $viabilityRate }}% Viable Batch
                                </span>
                            </div>
                            <p class="text-[11px] text-[#7A7A7A] dark:text-[#8E8B83]">
                                Phase: <strong class="text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->batch_day <= 25 ? 'Setter Phase' : 'Hatcher Phase' }}</strong> • 
                                Duck Breed: <strong class="text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->duck_breed ?? 'Pateros Mallard' }}</strong> • 
                                Capacity: <strong class="text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->egg_count ?? 120 }} Eggs</strong>
                            </p>
                        </div>

                        <!-- Candling Log Trigger Action -->
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="openVitalityModal()" class="egg-btn-primary px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>🔬</span>
                                <span>Record Candling Assessment</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Telematics Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-4 sm:mt-6 pt-4 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] text-center">
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Batch Total</span>
                        <span class="text-xs sm:text-sm font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->egg_count ?? 120 }} Eggs</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Incubation Day</span>
                        <span class="text-xs sm:text-sm font-black font-mono text-[#89B4C4]">Day {{ $chamber->batch_day ?? 14 }} / 28</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Batch Viability</span>
                        <span id="stat-batch-viability" class="text-xs sm:text-sm font-black font-mono text-emerald-600 dark:text-emerald-400">{{ $viabilityRate }}%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Auto-Turner</span>
                        <span class="text-xs sm:text-sm font-black font-mono text-amber-600 dark:text-amber-400">Active (45° Tilt)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Control Panel & PID Adjustment (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                    <div>
                        <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Hardware Control Panel</h2>
                        <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">Calibrate setpoints, PID parameters, and batch profiles</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-[#FFC107] text-[#1F1F1F]">
                        Live Sync
                    </span>
                </div>

                <form id="controls-form" onsubmit="saveChamberControls(event)" class="mt-4 space-y-4">
                    <!-- Temperature Setpoint -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="input-setpoint" class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider">
                                Incubation Target (°C)
                            </label>
                            <span class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">Duck Egg Standard: 37.5°C</span>
                        </div>
                        <div class="relative">
                            <input type="number" step="0.1" min="30.0" max="42.0" id="input-setpoint" name="setpoint" value="{{ $chamber->setpoint }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] font-mono font-bold text-sm outline-none">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-gray-400">°C</span>
                        </div>
                    </div>

                    <!-- Critical Runaway Threshold -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="input-critical" class="text-xs font-bold text-[#E63946] uppercase tracking-wider">
                                Critical Runaway Cutoff (°C)
                            </label>
                            <span class="text-xs text-red-500">Auto emergency trip</span>
                        </div>
                        <div class="relative">
                            <input type="number" step="0.1" min="36.0" max="45.0" id="input-critical" name="critical_temp" value="{{ $chamber->critical_temp }}" required class="w-full px-4 py-2.5 rounded-xl border border-red-300 dark:border-red-900 bg-white dark:bg-[#1A1917] focus:border-[#E63946] font-mono font-bold text-sm text-[#E63946] outline-none">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-red-400">°C</span>
                        </div>
                    </div>

                    <!-- PID Control Mode & Heater Toggle -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider block mb-1">PID Mode</label>
                            <select id="input-mode" name="mode" class="w-full px-3 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] font-bold text-xs bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] outline-none">
                                <option value="AUTO" {{ $chamber->mode === 'AUTO' ? 'selected' : '' }}>AUTO (Closed-Loop)</option>
                                <option value="MANUAL" {{ $chamber->mode === 'MANUAL' ? 'selected' : '' }}>MANUAL Override</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider block mb-1">Heater Relay</label>
                            <select id="input-heater" name="heater_state" class="w-full px-3 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] font-bold text-xs bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] outline-none">
                                <option value="1" {{ $chamber->heater_state ? 'selected' : '' }}>ON (Heating Active)</option>
                                <option value="0" {{ !$chamber->heater_state ? 'selected' : '' }}>OFF (Standby)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Exhaust Fan PWM Slider -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="input-fan" class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider">
                                Intake / Exhaust Fan Duty Cycle (PWM)
                            </label>
                            <span id="fan-slider-value" class="font-mono font-bold text-xs text-[#89B4C4]">{{ $chamber->fan_pwm }}%</span>
                        </div>
                        <input type="range" min="0" max="100" id="input-fan" name="fan_pwm" value="{{ $chamber->fan_pwm }}" oninput="document.getElementById('fan-slider-value').textContent = this.value + '%'" class="w-full accent-[#89B4C4] cursor-pointer">
                    </div>

                    <!-- Batch Information -->
                    <div class="pt-3 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] space-y-3">
                        <span class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider block">Incubation Batch Details</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] text-[#7A7A7A] dark:text-[#8E8B83] block font-medium mb-1">Batch Identifier</label>
                                <input type="text" name="batch_name" value="{{ $chamber->batch_name }}" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold">
                            </div>
                            <div>
                                <label class="text-[11px] text-[#7A7A7A] dark:text-[#8E8B83] block font-medium mb-1">Incubation Day</label>
                                <input type="number" min="1" max="35" name="batch_day" value="{{ $chamber->batch_day }}" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] text-[#7A7A7A] dark:text-[#8E8B83] block font-medium mb-1">Duck Breed</label>
                                <input type="text" name="duck_breed" value="{{ $chamber->duck_breed }}" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold">
                            </div>
                            <div>
                                <label class="text-[11px] text-[#7A7A7A] dark:text-[#8E8B83] block font-medium mb-1">Total Set Eggs</label>
                                <input type="number" min="1" name="egg_count" value="{{ $chamber->egg_count }}" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="save-controls-btn" class="w-full egg-btn-primary py-3 rounded-xl font-bold text-xs mt-2">
                        💾 Deploy Setpoint & Actuator Instructions
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Vitality Log: Embryo Candling Classifications -->
    <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Batch Candling & Embryo Vitality Log</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FFC107]/20 text-[#1F1F1F] dark:text-[#FFC107]">
                        Hatchery Audits
                    </span>
                </div>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                    Batch optical candling inspection history & embryo developmental milestones (Roxas Main Hatchery)
                </p>
            </div>

            <!-- Vitality Summary Badges & Action -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-bold bg-[#FAF9F6] dark:bg-[#1A1917] px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="text-[#3A3A3A] dark:text-[#F2EFE9]">Viability Ratio:</span>
                    <span id="vitality-rate-text" class="text-emerald-600 dark:text-emerald-400 font-extrabold font-mono text-sm">{{ $viabilityRate }}%</span>
                    <span class="text-[#7A7A7A] dark:text-[#8E8B83] font-normal">({{ $vitalityViable }}/{{ $vitalityTotal }} audits)</span>
                </div>

                <button onclick="openVitalityModal()" class="egg-btn-primary py-2 px-3 text-xs rounded-xl font-bold flex items-center gap-1.5 shadow-sm">
                    <span>🔬</span>
                    <span>+ Log Candling Assessment</span>
                </button>
            </div>
        </div>

        <!-- Vitality Table -->
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#D9CBB8] dark:border-[#3A3833] text-[#7A7A7A] dark:text-[#8E8B83] uppercase text-[10px] tracking-wider font-extrabold bg-[#FAF9F6] dark:bg-[#1A1917]">
                        <th class="py-3 px-4 rounded-l-xl">Timestamp</th>
                        <th class="py-3 px-4">Batch ID</th>
                        <th class="py-3 px-4">Inspection Stage / Milestone</th>
                        <th class="py-3 px-4">Candling Day</th>
                        <th class="py-3 px-4">Batch Assessment</th>
                        <th class="py-3 px-4">Optical Candling Diagnostic Notes</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody id="vitality-table-body" class="divide-y divide-[#D9CBB8]/40 dark:divide-[#3A3833] font-medium text-[#3A3A3A] dark:text-[#F2EFE9]">
                    @forelse($vitalityLogs as $vlog)
                        <tr class="hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824] transition">
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400 font-mono">{{ $vlog->created_at->format('M d, H:i:s') }}</td>
                            <td class="py-3 px-4 font-bold">{{ $vlog->batch_id }}</td>
                            <td class="py-3 px-4 font-bold text-[#89B4C4]">{{ $vlog->grid_position }}</td>
                            <td class="py-3 px-4 font-mono">Day {{ $vlog->candling_day ?? 14 }}</td>
                            <td class="py-3 px-4">
                                @if($vlog->result === 'viable')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                        OPTIMAL VIABILITY
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                        NON-VIABLE / CULLED
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $vlog->notes ?? 'Standard batch candling assessment' }}</td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="deleteVitalityLog({{ $vlog->id }})" class="text-gray-400 hover:text-red-600 font-bold p-1">
                                    ✕
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400">No candling records found. Click "+ Log Candling Assessment" to record one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: Record Batch Candling Assessment -->
<div id="vitality-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="egg-card bg-white dark:bg-[#22201D] p-6 max-w-md w-full rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833] shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-[#D9CBB8] dark:border-[#3A3833]">
            <div class="flex items-center gap-2">
                <span class="text-xl">🔬</span>
                <h3 class="font-extrabold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">Record Batch Candling Assessment</h3>
            </div>
            <button onclick="closeVitalityModal()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-black text-lg">&times;</button>
        </div>

        <form id="vitality-form" onsubmit="submitVitalityLog(event)" class="mt-4 space-y-3">
            <input type="hidden" name="chamber_id" value="{{ $chamber->id }}">
            <input type="hidden" name="batch_id" value="{{ $chamber->batch_name ?? 'BATCH-2026-D01' }}">

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Inspection Stage / Milestone</label>
                <input type="text" id="vit-position" name="grid_position" value="Day {{ $chamber->batch_day ?? 14 }} Candling Assessment" placeholder="e.g. Day 14 Mid-Term Candling" required class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Batch Viability Assessment</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex items-center gap-2 cursor-pointer hover:border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20">
                        <input type="radio" name="result" value="viable" checked class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">✅ Viable (Normal)</span>
                    </label>
                    <label class="p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex items-center gap-2 cursor-pointer hover:border-red-500 bg-red-50/50 dark:bg-red-950/20">
                        <input type="radio" name="result" value="non-viable" class="text-red-600 focus:ring-red-500">
                        <span class="text-xs font-bold text-red-800 dark:text-red-300">❌ Anomaly / Cull</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Candling Day</label>
                <input type="number" name="candling_day" value="{{ $chamber->batch_day ?? 14 }}" min="1" max="35" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Vascular / Embryonic Observations</label>
                <textarea name="notes" rows="2" placeholder="e.g. Robust chorioallantoic vascular network and active embryo movement confirmed via ESP32-CAM candling backlight." class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#D9CBB8] dark:border-[#3A3833]">
                <button type="button" onclick="closeVitalityModal()" class="px-4 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-gray-50 dark:hover:bg-[#2A2824]">
                    Cancel
                </button>
                <button type="submit" class="egg-btn-primary px-4 py-2 rounded-xl text-xs font-bold">
                    Save Assessment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentChamberId = {{ $chamber->id }};
    let currentSetpoint = {{ (float) $chamber->setpoint }};
    let currentCritical = {{ (float) $chamber->critical_temp }};
    let currentBatchDay = {{ (int) ($chamber->batch_day ?? 14) }};
    let streamInterval = null;
    let isStreamActive = true;
    let isAudioAlarmMuted = false;
    let thermalRunawayAlertActive = false;
    let lightingMode = 'standard';
    const zoomLevels = [1.0, 1.4, 1.8];
    let zoomIndex = 0;

    document.addEventListener('DOMContentLoaded', () => {
        renderEggClutch();
        startPollingStream();
        updateCameraClock();
        setInterval(updateCameraClock, 1000);
    });

    function updateCameraClock() {
        const clockEl = document.getElementById('camera-hud-clock');
        if (clockEl) {
            clockEl.textContent = new Date().toTimeString().split(' ')[0];
        }
    }

    function setLightingMode(mode) {
        lightingMode = mode;
        const btnStd = document.getElementById('btn-mode-standard');
        const btnCandle = document.getElementById('btn-mode-candling');
        const viewportBg = document.getElementById('camera-viewport-bg');
        const hudLighting = document.getElementById('hud-lighting-label');

        if (mode === 'candling') {
            btnCandle.className = 'px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#FFC107] text-[#1F1F1F] shadow-sm transition flex items-center gap-1';
            btnStd.className = 'px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] hover:text-[#3A3A3A] dark:hover:text-[#F2EFE9] transition flex items-center gap-1';
            viewportBg.className = 'relative w-full min-h-[300px] sm:min-h-[360px] flex items-center justify-center p-6 transition-all duration-500 bg-[#080706] overflow-hidden';
            hudLighting.textContent = '● OPTICAL CANDLING BACKLIGHT (VASCULAR GLOW)';
            hudLighting.className = 'text-amber-500 font-bold';
        } else {
            btnStd.className = 'px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white dark:bg-[#2D2B27] text-[#3A3A3A] dark:text-[#F2EFE9] shadow-sm transition flex items-center gap-1';
            btnCandle.className = 'px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] hover:text-[#3A3A3A] dark:hover:text-[#F2EFE9] transition flex items-center gap-1';
            viewportBg.className = 'relative w-full min-h-[300px] sm:min-h-[360px] flex items-center justify-center p-6 transition-all duration-500 bg-gradient-to-b from-[#252320] via-[#1B1917] to-[#11100F] overflow-hidden';
            hudLighting.textContent = '● DAYLIGHT INSPECTION LED (5500K)';
            hudLighting.className = 'text-amber-400 font-bold';
        }

        renderEggClutch();
    }

    function cycleZoom() {
        zoomIndex = (zoomIndex + 1) % zoomLevels.length;
        const currentZoom = zoomLevels[zoomIndex];
        const layer = document.getElementById('camera-zoom-layer');
        const btn = document.getElementById('btn-zoom');
        if (layer) {
            layer.style.transform = `scale(${currentZoom})`;
        }
        if (btn) {
            btn.textContent = `${currentZoom.toFixed(1)}x`;
        }
    }

    function renderEggClutch() {
        const container = document.getElementById('egg-clutch-display');
        if (!container) return;
        container.innerHTML = '';

        const eggConfig = [
            { rot: -10, scale: 1.0 },
            { rot: 6, scale: 1.02 },
            { rot: -5, scale: 0.98 },
            { rot: 12, scale: 1.04 },
            { rot: -8, scale: 1.0 },
            { rot: 15, scale: 1.02 },
            { rot: -14, scale: 0.99 },
            { rot: 4, scale: 1.01 },
            { rot: -3, scale: 1.03 },
            { rot: 9, scale: 0.98 },
            { rot: -7, scale: 1.0 },
            { rot: 11, scale: 1.02 },
        ];

        eggConfig.forEach((cfg) => {
            const egg = document.createElement('div');
            egg.className = 'relative w-12 h-16 sm:w-14 sm:h-20 transition-all duration-500 rounded-[50%/60%_60%_40%_40%]';
            egg.style.transform = `rotate(${cfg.rot}deg) scale(${cfg.scale})`;

            if (lightingMode === 'candling') {
                egg.style.background = 'radial-gradient(circle at 45% 45%, #FFE082 0%, #FF9800 45%, #BF360C 95%)';
                egg.style.boxShadow = '0 0 22px rgba(255, 152, 0, 0.7)';
                egg.innerHTML = `
                    <svg class="w-full h-full p-2 opacity-85 pointer-events-none" viewBox="0 0 100 100">
                        <circle cx="48" cy="46" r="9" fill="#5D1005" opacity="0.9" />
                        <circle cx="49" cy="45" r="3.5" fill="#2A0502" />
                        <path d="M48,46 Q35,35 24,30 M48,46 Q38,55 22,65 M48,46 Q60,38 76,32 M48,46 Q62,58 78,68 M48,46 Q46,25 45,15 M48,46 Q50,70 52,85" stroke="#7A180C" stroke-width="2.5" fill="none" stroke-linecap="round" />
                        <path d="M24,30 Q17,25 11,28 M22,65 Q15,72 9,70 M76,32 Q86,28 89,34 M78,68 Q85,76 90,74" stroke="#9E2A2B" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    </svg>
                `;
            } else {
                egg.style.background = 'radial-gradient(circle at 35% 35%, #FFFFFF 0%, #F5EFEB 50%, #D8CABB 100%)';
                egg.style.boxShadow = 'inset -2px -4px 6px rgba(0,0,0,0.22), 0 5px 8px rgba(0,0,0,0.5)';
            }

            container.appendChild(egg);
        });
    }

    function takeCameraSnapshot() {
        const vp = document.getElementById('camera-viewport-bg');
        if (vp) {
            const flash = document.createElement('div');
            flash.className = 'absolute inset-0 bg-white z-50 transition-opacity duration-300 opacity-90 pointer-events-none';
            vp.appendChild(flash);
            setTimeout(() => {
                flash.classList.add('opacity-0');
                setTimeout(() => flash.remove(), 300);
            }, 50);
        }
        window.SmartPalao.cart.showToast('📸 ESP32-CAM optical frame saved to chamber inspection archive.');
    }

    function triggerThermalRunawayAlert(maxTemp) {
        thermalRunawayAlertActive = true;
        const banner = document.getElementById('runaway-banner');
        banner.classList.remove('hidden');
        banner.classList.add('runaway-alert-active');

        if (!isAudioAlarmMuted) {
            window.SmartPalao.alertAudio.playAlarm();
        }
    }

    function clearThermalRunawayAlert() {
        thermalRunawayAlertActive = false;
        const banner = document.getElementById('runaway-banner');
        banner.classList.add('hidden');
        banner.classList.remove('runaway-alert-active');
    }

    function dismissAlert() {
        document.getElementById('runaway-banner').classList.add('hidden');
    }

    function emergencyCooldown() {
        fetch(`/api/telemetry/controls/${currentChamberId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                heater_state: 0,
                fan_pwm: 100,
                mode: 'MANUAL',
            })
        })
        .then(res => res.json())
        .then(data => {
            window.SmartPalao.cart.showToast('🚨 EMERGENCY COOLDOWN: Heater killed, exhaust fan driven to 100% PWM!');
            triggerSimulation(false);
        });
    }

    function toggleAudioAlert() {
        isAudioAlarmMuted = !isAudioAlarmMuted;
        window.SmartPalao.alertAudio.isMuted = isAudioAlarmMuted;
        document.getElementById('audio-icon').textContent = isAudioAlarmMuted ? '🔕' : '🔔';
        document.getElementById('audio-label').textContent = isAudioAlarmMuted ? 'Sound Alarm: MUTED' : 'Sound Alarm: ON';
    }

    function startPollingStream() {
        if (streamInterval) clearInterval(streamInterval);
        streamInterval = setInterval(() => {
            if (!isStreamActive) return;
            fetchLatestTelemetry();
        }, 2500);
    }

    function toggleStream() {
        isStreamActive = !isStreamActive;
        const btn = document.getElementById('toggle-stream-btn');
        const label = document.getElementById('stream-btn-label');
        if (isStreamActive) {
            btn.className = 'px-3.5 py-2 rounded-xl text-xs font-bold bg-[#89B4C4] text-white hover:bg-[#6496A9] transition flex items-center gap-1.5 shadow-sm';
            label.textContent = 'Stream: Active (2.5s)';
        } else {
            btn.className = 'px-3.5 py-2 rounded-xl text-xs font-bold bg-gray-400 text-white hover:bg-gray-500 transition flex items-center gap-1.5 shadow-sm';
            label.textContent = 'Stream: Paused';
        }
    }

    function fetchLatestTelemetry() {
        fetch(`/api/telemetry/latest/${currentChamberId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                const r = data.reading;
                const stats = data.stats;
                const ch = data.chamber;

                document.getElementById('metric-ambient-temp').textContent = r.ambient_temp.toFixed(2);
                document.getElementById('metric-humidity').textContent = r.humidity.toFixed(1);
                document.getElementById('last-sync-time').textContent = new Date().toLocaleTimeString();

                const deltaEl = document.getElementById('temp-delta-badge');
                const delta = stats.temp_delta;
                deltaEl.textContent = (delta >= 0 ? '+' : '') + delta.toFixed(2) + '°C';

                const heaterText = document.getElementById('heater-status-text');
                heaterText.textContent = ch.heater_state ? '🔥 ACTIVE (ON)' : '⚪ STANDBY (OFF)';
                heaterText.className = ch.heater_state ? 'text-sm font-black font-mono text-amber-600 dark:text-amber-400' : 'text-sm font-black font-mono text-gray-400';

                document.getElementById('fan-status-text').textContent = `🌀 ${ch.fan_pwm}%`;

                if (r.ambient_temp >= currentCritical) {
                    triggerThermalRunawayAlert(r.ambient_temp);
                } else {
                    clearThermalRunawayAlert();
                }
            })
            .catch(err => console.error('Telemetry fetch error:', err));
    }

    function triggerSimulation(isSpike) {
        fetch('/api/telemetry/simulate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                chamber_id: currentChamberId,
                spike: isSpike,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (isSpike) {
                window.SmartPalao.cart.showToast('🔥 Injected Thermal Runaway Spike! Breached critical ceiling.', 'danger');
            } else {
                window.SmartPalao.cart.showToast('🔄 Simulated healthy ESP32 telemetry packet.');
            }
            fetchLatestTelemetry();
        });
    }

    function saveChamberControls(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('save-controls-btn');
        btn.disabled = true;
        btn.textContent = 'Deploying Instructions...';

        const payload = {
            setpoint: parseFloat(form.setpoint.value),
            critical_temp: parseFloat(form.critical_temp.value),
            mode: form.mode.value,
            heater_state: parseInt(form.heater_state.value),
            fan_pwm: parseInt(form.fan_pwm.value),
            batch_name: form.batch_name.value,
            batch_day: parseInt(form.batch_day.value),
            duck_breed: form.duck_breed.value,
            egg_count: parseInt(form.egg_count.value),
        };

        fetch(`/api/telemetry/controls/${currentChamberId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = '💾 Deploy Setpoint & Actuator Instructions';
            if (data.success) {
                currentSetpoint = payload.setpoint;
                currentCritical = payload.critical_temp;
                document.querySelectorAll('.setpoint-display').forEach(el => el.textContent = currentSetpoint.toFixed(2) + '°C');
                document.querySelectorAll('.setpoint-critical').forEach(el => el.textContent = currentCritical.toFixed(2) + '°C');
                window.SmartPalao.cart.showToast('✅ Incubator calibration deployed to ESP32 PID controller!');
                fetchLatestTelemetry();
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = '💾 Deploy Setpoint & Actuator Instructions';
            console.error('Error saving controls:', err);
        });
    }

    function openVitalityModal() {
        document.getElementById('vitality-modal').classList.remove('hidden');
    }

    function closeVitalityModal() {
        document.getElementById('vitality-modal').classList.add('hidden');
    }

    function quickOpenCandleWithCoord(coord) {
        document.getElementById('vit-position').value = coord;
        openVitalityModal();
    }

    function submitVitalityLog(e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            chamber_id: parseInt(form.chamber_id.value),
            batch_id: form.batch_id.value,
            grid_position: form.grid_position.value,
            result: form.result.value,
            candling_day: parseInt(form.candling_day.value),
            notes: form.notes.value,
        };

        fetch('/api/vitality/logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeVitalityModal();
                form.reset();
                window.SmartPalao.cart.showToast(`Batch candling assessment recorded (${payload.result.toUpperCase()})`);
                reloadVitalityTable();
            }
        });
    }

    function reloadVitalityTable() {
        fetch(`/api/vitality/logs?chamber_id=${currentChamberId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;
                const tbody = document.getElementById('vitality-table-body');
                tbody.innerHTML = '';

                data.logs.forEach(log => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824] transition';
                    const isViable = log.result === 'viable';
                    tr.innerHTML = `
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                        <td class="py-3 px-4 font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">${log.batch_id}</td>
                        <td class="py-3 px-4 font-bold text-[#89B4C4]">${log.grid_position}</td>
                        <td class="py-3 px-4 font-mono">${log.candling_day ? 'Day ' + log.candling_day : 'Day 14'}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] ${isViable ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800'}">
                                <span class="w-1.5 h-1.5 rounded-full ${isViable ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-red-600 dark:bg-red-400'}"></span>
                                ${isViable ? 'OPTIMAL VIABILITY' : 'NON-VIABLE / CULLED'}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">${log.notes || 'Standard batch candling assessment'}</td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="deleteVitalityLog(${log.id})" class="text-gray-400 hover:text-red-600 font-bold p-1">✕</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                const rate = data.stats.viability_rate + '%';
                const el1 = document.getElementById('vitality-rate-text');
                const el2 = document.getElementById('stat-batch-viability');
                if (el1) el1.textContent = rate;
                if (el2) el2.textContent = rate;
            });
    }

    function deleteVitalityLog(id) {
        if (!confirm('Delete this candling record?')) return;
        fetch(`/api/vitality/logs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(res => res.json())
        .then(data => {
            window.SmartPalao.cart.showToast('Vitality log entry removed.');
            reloadVitalityTable();
        });
    }
</script>
@endpush
