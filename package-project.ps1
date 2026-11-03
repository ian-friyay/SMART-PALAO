# Smart Palao Team Packaging Script
# Creates a clean, ultra-lightweight ZIP archive for groupmates

$sourceDir = $PSScriptRoot
$parentDir = Split-Path $sourceDir -Parent
$outputZip = Join-Path $parentDir "Smart-Palao-Team-Package.zip"
$tempDir = Join-Path $env:TEMP "smart_palao_export_$(Get-Random)"

Write-Host "==========================================================" -ForegroundColor Yellow
Write-Host "       Packaging Smart Palao Project for Team Sharing      " -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Yellow

# Ensure database dump is fresh
Write-Host "`n[1/4] Ensuring database dump is up to date..." -ForegroundColor Cyan
if (Test-Path "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe") {
    & "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe" -u root --databases smart_palao | Out-File -FilePath "$sourceDir\database\smart_palao.sql" -Encoding utf8
    Write-Host " -> Exported database/smart_palao.sql" -ForegroundColor Green
}

# Create temp staging folder
Write-Host "`n[2/4] Staging clean project files (excluding vendor & node_modules)..." -ForegroundColor Cyan
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

$excludeFolders = @('vendor', 'node_modules', '.git', 'storage\logs', '.vite-temp')

Get-ChildItem -Path $sourceDir -Recurse | ForEach-Object {
    $relativePath = $_.FullName.Substring($sourceDir.Length + 1)
    
    # Check if inside any excluded folder
    $isExcluded = $false
    foreach ($ex in $excludeFolders) {
        if ($relativePath -like "$ex*" -or $relativePath -like "*\$ex*") {
            $isExcluded = $true
            break
        }
    }

    # Also exclude previous zip files
    if ($_.Extension -eq ".zip") {
        $isExcluded = $true
    }

    if (-not $isExcluded) {
        $targetPath = Join-Path $tempDir $relativePath
        if ($_.PSIsContainer) {
            New-Item -ItemType Directory -Path $targetPath -Force | Out-Null
        } else {
            $targetParent = Split-Path $targetPath -Parent
            if (-not (Test-Path $targetParent)) {
                New-Item -ItemType Directory -Path $targetParent -Force | Out-Null
            }
            Copy-Item -Path $_.FullName -Destination $targetPath -Force
        }
    }
}

# Ensure storage/logs directory exists in staging
New-Item -ItemType Directory -Path (Join-Path $tempDir "storage\logs") -Force | Out-Null
New-Item -ItemType File -Path (Join-Path $tempDir "storage\logs\.gitignore") -Force | Out-Null

# Create ZIP
Write-Host "`n[3/4] Compressing into team package..." -ForegroundColor Cyan
if (Test-Path $outputZip) {
    Remove-Item $outputZip -Force
}

Compress-Archive -Path "$tempDir\*" -DestinationPath $outputZip -CompressionLevel Optimal

# Cleanup temp
Remove-Item -Path $tempDir -Recurse -Force | Out-Null

$zipSize = (Get-Item $outputZip).Length / 1MB
Write-Host "`n[4/4] Done!" -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Green
Write-Host " Archive Created: $outputZip" -ForegroundColor Yellow
Write-Host (" Size:             {0:N2} MB" -f $zipSize) -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "You can now send this file via Messenger, Discord, Google Drive, or USB!"
