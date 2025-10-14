protected function schedule(Schedule $schedule): void
{
    $schedule->command('kick:refresh')->everyTenMinutes();
}
