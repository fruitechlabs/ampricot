// Headers
#include "winsparkle.h"
#include <windows.h>
#include <windowsx.h>
#include <stdio.h>
#include <tchar.h>

// Constants
#define MAIN_WIN_CLASS_NAME    _TEXT("app_psdk_main_win_class")
#define IDB_CHECK_FOR_UPDATES   100

// Globals
HINSTANCE g_hInstance;
HWND g_hwndMain;

// Callbacks
void OnCheckForUpdates(HWND hwnd, int id, HWND hwndCtl, UINT codeNotify)
{
    if ( id == IDB_CHECK_FOR_UPDATES )
    {
        win_sparkle_check_update_with_ui();
    }
}

void OnDestroy(HWND hwnd)
{
    /* Perform proper shutdown of WinSparkle. Notice that it's done while
       the main window is still visible, so that the app doesn't do 
       things when it appears to have quit already. */
    win_sparkle_cleanup();

    PostQuitMessage(0);
}

LRESULT CALLBACK MainWndProc(HWND hwnd, UINT msg, WPARAM wParam, LPARAM lParam)
{
    switch (msg)
    {
        HANDLE_MSG(hwnd, WM_COMMAND, OnCheckForUpdates);
        HANDLE_MSG(hwnd, WM_DESTROY, OnDestroy);

        default:
            return DefWindowProc(hwnd, msg, wParam, lParam);
    }

    return 0;
}

// Initialization Functions
int RegisterMainClass()
{
    WNDCLASS wc;
    ZeroMemory(&wc, sizeof(wc));
    wc.style         = CS_DBLCLKS | CS_HREDRAW | CS_VREDRAW;
    wc.lpfnWndProc   = MainWndProc;
    wc.hInstance     = g_hInstance;
    wc.hIcon         = LoadIcon(NULL, IDI_APPLICATION);
    wc.hCursor       = LoadCursor(NULL, IDC_ARROW);
    wc.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
    wc.lpszClassName = MAIN_WIN_CLASS_NAME;

    return RegisterClass(&wc) != 0;
}

// Entry Point
int APIENTRY WinMain(HINSTANCE hInstance, HINSTANCE hPrevInstance, LPSTR lpCmdLine, int nCmdShow)
{
    g_hInstance = hInstance;

    if (!RegisterMainClass())
	{
        return 1;
	}

    /* initialize WinSparkle as soon as the app itself is initialized, right
       before entering the event loop: */
    win_sparkle_init();

    {
        MSG msg;
        while (GetMessage(&msg, NULL, 0, 0))
        {
            TranslateMessage(&msg);
            DispatchMessage(&msg);
        }
    }

    return 0;
}
