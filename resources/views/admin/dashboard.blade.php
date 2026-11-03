@extends('layouts.app')

@section('title', 'IoT Incubation Dashboard - Smart Palao (Owner Only)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" id="incubation-dashboard">

    <!-- Top Header & Status Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-[#22201D] p-6 rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF8E1] dark:bg-[#2A261A] flex items-center justify-center p-1 border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm">
                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">Technical Incubation Dashboard</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-[#FFC107] text-[#1F1F1F]">
                        Owner Level
                    </span>
                </div>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                    Chamber: <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $chamber->name }}</span> ({{ $chamber->location }}) • Telemetry: <span class="font-mono text-emerald-600 font-bold">ONLINE</span>
                </p>
            </div>
        </div>

        <!-- Telemetry Simulator & Live Actions -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Audio Alert Toggle -->
            <button id="toggle-audio-btn" onclick="toggleAudioAlert()" class="px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] bg-[#F4F1EA] dark:bg-[#1A1917] hover:bg-[#FAF8F5] dark:hover:bg-[#262420] transition flex items-center gap-1.5 shadow-sm" title="Toggle audible alarm for thermal runaway">
                <span id="audio-icon">🔔</span>
                <span id="audio-label">Sound Alarm: ON</span>
            </button>

            <!-- Live Polling Toggle -->
            <button id="toggle-stream-btn" onclick="toggleStream()" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-[#89B4C4] text-white hover:bg-[#6496A9] transition flex items-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                <span id="stream-btn-label">Stream: Active (2.5s)</span>
            </button>

            <!-- Inject Normal ESP32 Packet -->
            <button onclick="triggerSimulation(false)" class="px-3 py-2 rounded-xl text-xs font-bold bg-[#FAF8F5] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition flex items-center gap-1">
                <span>🔄 Simulate Packet</span>
            </button>

            <!-- Inject Thermal Runaway Spike -->
            <button onclick="triggerSimulation(true)" class="px-3 py-2 rounded-xl text-xs font-bold bg-[#E63946] text-white hover:bg-red-700 transition flex items-center gap-1 shadow-sm" title="Force AMG8833 temperatures above critical threshold to test thermal runaway alerts">
                <span>🔥 Inject Thermal Spike</span>
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

    <!-- Main Grid: Thermal Heatmap (AMG8833) & Control Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: Real-Time 8x8 Thermal Heatmap (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">AMG8833 Thermal Heatmap</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4]">
                                8x8 Grid (64 Sensels)
                            </span>
                        </div>
                        <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">Real-time egg surface infrared distribution across tray matrix</p>
                    </div>

                    <!-- Heatmap Legend -->
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-[#5A5A5A] dark:text-[#A8A49C]">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#89B4C4]"></span> Cool (&lt;36.8°)</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#D9CBB8]"></span> Eq (37.2°)</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#FFC107]"></span> Opt (37.5°)</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#E63946]"></span> Crit (&ge;38.5°)</span>
                    </div>
                </div>

                <!-- 8x8 Heatmap Container -->
                <div class="mt-6 flex flex-col items-center">
                    <div class="relative w-full max-w-lg aspect-square">
                        <div id="thermal-grid-container" class="heatmap-grid w-full h-full shadow-inner">
                            <!-- 64 Cells dynamically rendered by JavaScript -->
                        </div>
                    </div>

                    <!-- Cell Inspection Popover / Display -->
                    <div id="cell-inspector" class="w-full max-w-lg mt-4 p-3.5 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-4 h-4 rounded bg-[#FFC107] border border-[#D9CBB8] dark:border-[#3A3833] inline-block" id="inspector-color"></span>
                            <div>
                                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]" id="inspector-coord">Hover on any cell to inspect</span>
                                <span class="text-[#7A7A7A] dark:text-[#8E8B83] block text-[11px]" id="inspector-desc">Surface temperature variation indicator</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]" id="inspector-temp">--.-°C</span>
                            <span class="block text-[10px] font-bold uppercase text-[#89B4C4]" id="inspector-status">Idle</span>
                        </div>
                    </div>
                </div>

                <!-- Heatmap Summary Stats -->
                <div class="grid grid-cols-4 gap-2 mt-6 pt-4 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] text-center">
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Max Sensel</span>
                        <span id="stat-max-temp" class="text-sm font-black font-mono text-[#E63946]">--.-°C</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Min Sensel</span>
                        <span id="stat-min-temp" class="text-sm font-black font-mono text-[#89B4C4]">--.-°C</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Avg Surface</span>
                        <span id="stat-avg-temp" class="text-sm font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">--.-°C</span>
                    </div>
                    <div class="p-2 rounded-xl bg-[#FAF8F5] dark:bg-[#1A1917]">
                        <span class="text-[10px] font-bold text-[#7A7A7A] dark:text-[#8E8B83] uppercase block">Uniformity</span>
                        <span id="stat-uniformity" class="text-sm font-black font-mono text-emerald-600 dark:text-emerald-400">98.4%</span>
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
                    <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Embryo Vitality Classification Log</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FFC107]/20 text-[#1F1F1F] dark:text-[#FFC107]">
                        Candling Audits
                    </span>
                </div>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                    Classified embryo vitality records (Viable vascular mesh vs. Non-viable infertile/blood ring) mapped to tray sensels
                </p>
            </div>

            <!-- Vitality Summary Badges & Quick Log Action -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-bold bg-[#FAF9F6] dark:bg-[#1A1917] px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="text-[#3A3A3A] dark:text-[#F2EFE9]">Viability Ratio:</span>
                    <span id="vitality-rate-text" class="text-emerald-600 dark:text-emerald-400 font-extrabold font-mono text-sm">{{ $viabilityRate }}%</span>
                    <span class="text-[#7A7A7A] dark:text-[#8E8B83] font-normal">({{ $vitalityViable }}/{{ $vitalityTotal }} checked)</span>
                </div>

                <button onclick="openVitalityModal()" class="egg-btn-primary py-2 px-3 text-xs rounded-xl font-bold">
                    + Log Egg Candling
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
                        <th class="py-3 px-4">Grid Sensel Position</th>
                        <th class="py-3 px-4">Candling Day</th>
                        <th class="py-3 px-4">Viability Result</th>
                        <th class="py-3 px-4">Morphology / Diagnostic Notes</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody id="vitality-table-body" class="divide-y divide-[#D9CBB8]/40 dark:divide-[#3A3833] font-medium text-[#3A3A3A] dark:text-[#F2EFE9]">
                    @forelse($vitalityLogs as $vlog)
                        <tr class="hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824] transition">
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400 font-mono">{{ $vlog->created_at->format('M d, H:i:s') }}</td>
                            <td class="py-3 px-4 font-bold">{{ $vlog->batch_id }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-[#89B4C4]">{{ $vlog->grid_position }}</td>
                            <td class="py-3 px-4 font-mono">Day {{ $vlog->candling_day ?? 14 }}</td>
                            <td class="py-3 px-4">
                                @if($vlog->result === 'viable')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                        VIABLE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                        NON-VIABLE
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $vlog->notes ?? 'Standard candling assessment' }}</td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="deleteVitalityLog({{ $vlog->id }})" class="text-gray-400 hover:text-red-600 font-bold p-1">
                                    ✕
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400">No vitality records found. Click "+ Log Egg Candling" to add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: Log Egg Vitality Result -->
<div id="vitality-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="egg-card bg-white dark:bg-[#22201D] p-6 max-w-md w-full rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833] shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-[#D9CBB8] dark:border-[#3A3833]">
            <div class="flex items-center gap-2">
                <span class="text-xl">🔬</span>
                <h3 class="font-extrabold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">Log Embryo Candling Viability</h3>
            </div>
            <button onclick="closeVitalityModal()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-black text-lg">&times;</button>
        </div>

        <form id="vitality-form" onsubmit="submitVitalityLog(event)" class="mt-4 space-y-3">
            <input type="hidden" name="chamber_id" value="{{ $chamber->id }}">
            <input type="hidden" name="batch_id" value="{{ $chamber->batch_name ?? 'BATCH-2026-D01' }}">

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Tray Grid Position (Row 1-8, Col 1-8)</label>
                <input type="text" id="vit-position" name="grid_position" placeholder="e.g. Row 3, Col 4" required class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Viability Classification</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex items-center gap-2 cursor-pointer hover:border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20">
                        <input type="radio" name="result" value="viable" checked class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">✅ Viable</span>
                    </label>
                    <label class="p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex items-center gap-2 cursor-pointer hover:border-red-500 bg-red-50/50 dark:bg-red-950/20">
                        <input type="radio" name="result" value="non-viable" class="text-red-600 focus:ring-red-500">
                        <span class="text-xs font-bold text-red-800 dark:text-red-300">❌ Non-Viable</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Candling Day</label>
                <input type="number" name="candling_day" value="{{ $chamber->batch_day }}" min="1" max="35" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Vascular / Embryonic Observations</label>
                <textarea name="notes" rows="2" placeholder="e.g., Distinct spider vascular network observed, heart pulsating." class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#D9CBB8] dark:border-[#3A3833]">
                <button type="button" onclick="closeVitalityModal()" class="px-4 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-gray-50 dark:hover:bg-[#2A2824]">
                    Cancel
                </button>
                <button type="submit" class="egg-btn-primary px-4 py-2 rounded-xl text-xs font-bold">
                    Save Record
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
    let streamInterval = null;
    let isStreamActive = true;
    let isAudioAlarmMuted = false;
    let thermalRunawayAlertActive = false;

    let initialGrid = @json($latestReading->thermal_grid_json ?? []);

    document.addEventListener('DOMContentLoaded', () => {
        renderHeatmapGrid(initialGrid);
        startPollingStream();
    });

    function renderHeatmapGrid(gridData) {
        const container = document.getElementById('thermal-grid-container');
        if (!container) return;

        if (!Array.isArray(gridData) || gridData.length !== 64) {
            gridData = new Array(64).fill(currentSetpoint);
        }

        container.innerHTML = '';
        let maxVal = -Infinity;
        let minVal = Infinity;
        let sum = 0;

        gridData.forEach((temp, index) => {
            temp = parseFloat(temp);
            maxVal = Math.max(maxVal, temp);
            minVal = Math.min(minVal, temp);
            sum += temp;

            const row = Math.floor(index / 8) + 1;
            const col = (index % 8) + 1;

            const colorSpec = window.SmartPalao.getThermalColor(temp, currentSetpoint, currentCritical);

            const cell = document.createElement('div');
            cell.className = 'heatmap-cell';
            cell.style.backgroundColor = colorSpec.bg;
            cell.style.color = colorSpec.text;
            cell.textContent = temp.toFixed(1);
            cell.dataset.row = row;
            cell.dataset.col = col;
            cell.dataset.temp = temp.toFixed(2);
            cell.dataset.label = colorSpec.label;

            cell.addEventListener('mouseenter', () => inspectCell(row, col, temp, colorSpec));
            cell.addEventListener('click', () => {
                inspectCell(row, col, temp, colorSpec);
                quickOpenCandleWithCoord(`Row ${row}, Col ${col}`);
            });

            container.appendChild(cell);
        });

        const avg = sum / 64;
        document.getElementById('stat-max-temp').textContent = maxVal.toFixed(2) + '°C';
        document.getElementById('stat-min-temp').textContent = minVal.toFixed(2) + '°C';
        document.getElementById('stat-avg-temp').textContent = avg.toFixed(2) + '°C';

        if (maxVal >= currentCritical) {
            triggerThermalRunawayAlert(maxVal);
        } else {
            clearThermalRunawayAlert();
        }
    }

    function inspectCell(row, col, temp, colorSpec) {
        document.getElementById('inspector-coord').textContent = `Sensel Coordinate: Row ${row}, Col ${col}`;
        document.getElementById('inspector-temp').textContent = temp.toFixed(2) + '°C';
        document.getElementById('inspector-color').style.backgroundColor = colorSpec.bg;
        document.getElementById('inspector-status').textContent = colorSpec.label;
        document.getElementById('inspector-status').style.color = colorSpec.bg === '#E63946' ? '#E63946' : '#89B4C4';
        
        const delta = (temp - currentSetpoint).toFixed(2);
        document.getElementById('inspector-desc').textContent = `Offset from setpoint: ${delta > 0 ? '+' : ''}${delta}°C • Click to candle`;
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

                if (r.thermal_grid) {
                    renderHeatmapGrid(r.thermal_grid);
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
                window.SmartPalao.cart.showToast(`Egg candling result recorded (${payload.result.toUpperCase()})`);
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
                        <td class="py-3 px-4 font-mono font-bold text-[#89B4C4]">${log.grid_position}</td>
                        <td class="py-3 px-4 font-mono">${log.candling_day ? 'Day ' + log.candling_day : 'Day 14'}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] ${isViable ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800'}">
                                <span class="w-1.5 h-1.5 rounded-full ${isViable ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-red-600 dark:bg-red-400'}"></span>
                                ${log.result.toUpperCase()}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">${log.notes || 'Standard candling assessment'}</td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="deleteVitalityLog(${log.id})" class="text-gray-400 hover:text-red-600 font-bold p-1">✕</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                document.getElementById('vitality-rate-text').textContent = data.stats.viability_rate + '%';
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
