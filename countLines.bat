@echo off
setlocal enabledelayedexpansion

set "rootDir=%~dp0"
set "fileCount=0"
set "lineCount=0"

for /r "%rootDir%" %%F in (*.php) do (
    set /a fileCount+=1
    for /f %%L in ('type "%%F" ^| find /v /c ""') do (
        set /a lineCount+=%%L
        :: Overwrite the existing line with the new information
        echo Total lines:!lineCount!, Total.php files:!fileCount!
    )
)

endlocal
