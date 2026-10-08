param(
    [string] $TaskName = 'YakinikuKing Laravel Scheduler',
    [string] $ProjectPath = (Split-Path -Parent (Split-Path -Parent $PSScriptRoot)),
    [string] $PhpPath = 'php'
)

$artisanPath = Join-Path $ProjectPath 'artisan'

if (-not (Test-Path -LiteralPath $artisanPath)) {
    throw "Laravel artisan file was not found at '$artisanPath'. Pass the project root with -ProjectPath."
}

$phpCommand = Get-Command $PhpPath -ErrorAction SilentlyContinue
if ($phpCommand) {
    $phpExecutable = $phpCommand.Source
} elseif (Test-Path -LiteralPath $PhpPath) {
    $phpExecutable = (Resolve-Path -LiteralPath $PhpPath).Path
} else {
    throw "PHP executable '$PhpPath' was not found. Pass its full path with -PhpPath."
}

$action = New-ScheduledTaskAction `
    -Execute $phpExecutable `
    -Argument ('"{0}" schedule:run' -f $artisanPath) `
    -WorkingDirectory $ProjectPath
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date).Date.AddMinutes(1) -RepetitionInterval (New-TimeSpan -Minutes 1)
$settings = New-ScheduledTaskSettingsSet -StartWhenAvailable -MultipleInstances IgnoreNew

Register-ScheduledTask `
    -TaskName $TaskName `
    -Action $action `
    -Trigger $trigger `
    -Settings $settings `
    -Description 'Runs the Laravel scheduler every minute for Yakiniku King.' `
    -Force | Out-Null

Write-Host "Registered '$TaskName' to run php artisan schedule:run every minute."
