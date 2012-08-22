/*

NSIS Modern User Interface
MySQL Options page

*/

;--------------------------------
;Page interface settings and variables

!macro MUI_MYSQLOPTSPAGE_INTERFACE

  !ifndef MUI_MYSQLOPTSPAGE_INTERFACE
  
    !define MUI_MYSQLOPTSPAGE_INTERFACE

  !endif

!macroend


;--------------------------------
;Page declaration

!macro MUI_PAGEDECLARATION_MYSQLOPTS

  !insertmacro MUI_SET MUI_${MUI_PAGE_UNINSTALLER_PREFIX}MYSQLOPTSPAGE ""
  !insertmacro MUI_MYSQLOPTSPAGE_INTERFACE  

  PageEx ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}custom

    PageCallbacks ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.MySQLOptsShow_${MUI_UNIQUEID} ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.MySQLOptsLeave_${MUI_UNIQUEID}

    Caption " "

  PageExEnd

  !insertmacro MUI_FUNCTION_MYSQLOPTSPAGE ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.MySQLOptsShow_${MUI_UNIQUEID} ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.MySQLOptsLeave_${MUI_UNIQUEID}

!macroend

!macro MUI_PAGE_MYSQLOPTS

  !verbose push
  !verbose ${MUI_VERBOSE}

  !insertmacro MUI_PAGE_INIT
  !insertmacro MUI_PAGEDECLARATION_MYSQLOPTS

  !verbose pop

!macroend

!macro MUI_UNPAGE_MYSQLOPTS

  !verbose push
  !verbose ${MUI_VERBOSE}

  !insertmacro MUI_UNPAGE_INIT
  !insertmacro MUI_PAGEDECLARATION_MYSQLOPTS

  !verbose pop

!macroend


;--------------------------------
;Page functions

!macro MUI_FUNCTION_MYSQLOPTSPAGE SHOW LEAVE

  Function "${SHOW}"

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM PRE
    !insertmacro MUI_HEADER_TEXT_PAGE "$(^MySQLPageTitle)" "$(^MySQLPageDesc)"

    nsDialogs::Create 1018
    Pop $mui.MySQLOptsPage

    ${If} $mui.MySQLOptsPage == error
        Abort
    ${EndIf}

    ${NSD_CreateLabel} 0 0 48% 12u "$(^MySQLPageOptsRootPass)"
    Pop $mui.MySQLOptsPage.RootPass.LBL

    ${NSD_CreatePassword} 0 13u 48% 12u $mui.MySQLOptsPage.RootPass.VAL
    Pop $mui.MySQLOptsPage.RootPass.TXT
    ${NSD_OnChange} $mui.MySQLOptsPage.RootPass.TXT mui.MySQLOptsPage.CheckEmptyOrNot


    ${NSD_CreateLabel} 50% 0 48% 12u "$(^MySQLPageOptsConfirmPass)"
    Pop $mui.MySQLOptsPage.ConfirmPass.LBL

    ${NSD_CreatePassword} 50% 13u 48% 12u $mui.MySQLOptsPage.ConfirmPass.VAL
    Pop $mui.MySQLOptsPage.ConfirmPass.TXT
    ${NSD_OnChange} $mui.MySQLOptsPage.ConfirmPass.TXT mui.MySQLOptsPage.CheckEmptyOrNot


    ${NSD_CreateLabel} 0 33u 100% 12u "$(^MySQLPageOptsServerPort)"
    Pop $mui.MySQLOptsPage.ServerPort.LBL

    ${NSD_CreateNumber} 0 46u 10% 12u $mui.MySQLOptsPage.ServerPort.VAL
    Pop $mui.MySQLOptsPage.ServerPort.TXT
    ${NSD_OnChange} $mui.MySQLOptsPage.ServerPort.TXT mui.MySQLOptsPage.CheckEmptyOrNot

    ${If} $mui.MySQLOptsPage.RootPass.VAL == ""
    ${OrIf} $mui.MySQLOptsPage.ConfirmPass.VAL == ""
    ${OrIf} $mui.MySQLOptsPage.ServerPort.VAL == ""
    ${OrIf} $mui.MySQLOptsPage.RootPass.VAL != $mui.MySQLOptsPage.ConfirmPass.VAL
        GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
        EnableWindow $3 0 ; disable next/install button
    ${EndIf}

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM SHOW

    nsDialogs::Show

  FunctionEnd

  Function "${LEAVE}"

    Call mui.MySQLOptsPage.CheckEmptyOrNot

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM LEAVE

  FunctionEnd

!macroend


Function mui.MySQLOptsPage.UpdateState

    ${NSD_GetText}  $mui.MySQLOptsPage.RootPass.TXT      $mui.MySQLOptsPage.RootPass.VAL
    ${NSD_GetText}  $mui.MySQLOptsPage.ConfirmPass.TXT   $mui.MySQLOptsPage.ConfirmPass.VAL
    ${NSD_GetText}  $mui.MySQLOptsPage.ServerPort.TXT    $mui.MySQLOptsPage.ServerPort.VAL

    ${str_trim} $mui.MySQLOptsPage.RootPass.VAL     $mui.MySQLOptsPage.RootPass.VAL
    ${str_trim} $mui.MySQLOptsPage.ConfirmPass.VAL  $mui.MySQLOptsPage.ConfirmPass.VAL

FunctionEnd

Function mui.MySQLOptsPage.CheckEmptyOrNot

    Call mui.MySQLOptsPage.UpdateState

    StrCmp $mui.MySQLOptsPage.RootPass.VAL      "" haltproc
    StrCmp $mui.MySQLOptsPage.ConfirmPass.VAL   "" haltproc
    StrCmp $mui.MySQLOptsPage.ServerPort.VAL    "" haltproc
    StrCmp $mui.MySQLOptsPage.RootPass.VAL $mui.MySQLOptsPage.ConfirmPass.VAL proceed haltproc

    haltproc:
    GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
    EnableWindow $3 0           ; disable next/install button
    Abort                       ; Return to the page

    proceed:
    GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
    EnableWindow $3 1           ; enable next/install button

FunctionEnd
