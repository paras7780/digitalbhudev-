param(
    [string]$Database = "bhudevsetu",
    [string]$HostName = "127.0.0.1",
    [int]$Port = 3306,
    [string]$Username = "root",
    [string]$DumpExe = "C:\xampp\mysql\bin\mysqldump.exe",
    [string]$OutputDirectory = ".\backups",
    [string]$DefaultsFile = ""
)
$ErrorActionPreference = "Stop"
if (-not (Test-Path $DumpExe)) { throw "mysqldump not found at '$DumpExe'. Update -DumpExe to your XAMPP MySQL bin path." }
New-Item -ItemType Directory -Force -Path $OutputDirectory | Out-Null
$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$outputFile = Join-Path $OutputDirectory "$Database-$timestamp.sql"
$passwordPtr = [IntPtr]::Zero
try {
    if ($DefaultsFile -and (Test-Path $DefaultsFile)) {
        # For unattended scheduled backups, protect this file with restrictive NTFS ACLs.
        & $DumpExe "--defaults-extra-file=$DefaultsFile" "--host=$HostName" "--port=$Port" "--single-transaction" "--routines" "--triggers" $Database "--result-file=$outputFile"
    } else {
        $passwordSecure = Read-Host "MySQL password for $Username" -AsSecureString
        $passwordPtr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($passwordSecure)
        $password = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($passwordPtr)
        # Interactive mode is for manual backups; avoid on shared machines.
        & $DumpExe "--host=$HostName" "--port=$Port" "--user=$Username" "--password=$password" "--single-transaction" "--routines" "--triggers" $Database "--result-file=$outputFile"
    }
    if ($LASTEXITCODE -ne 0) { Remove-Item $outputFile -ErrorAction SilentlyContinue; throw "mysqldump failed with exit code $LASTEXITCODE." }
    Write-Host "Database backup created: $((Resolve-Path $outputFile).Path)"
}
finally {
    if ($passwordPtr -ne [IntPtr]::Zero) { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($passwordPtr) }
    $password = $null
}
