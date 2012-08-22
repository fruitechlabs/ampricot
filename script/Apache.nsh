/*

NSIS Modern User Interface
Apache Options page

*/

;--------------------------------
;Page interface settings and variables

!macro MUI_APACHEOPTSPAGE_INTERFACE

  !ifndef MUI_APACHEOPTSPAGE_INTERFACE
  
    !define MUI_APACHEOPTSPAGE_INTERFACE

  !endif

!macroend


;--------------------------------
;Page declaration

!macro MUI_PAGEDECLARATION_APACHEOPTS

  !insertmacro MUI_SET MUI_${MUI_PAGE_UNINSTALLER_PREFIX}APACHEOPTSPAGE ""
  !insertmacro MUI_APACHEOPTSPAGE_INTERFACE  

  PageEx ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}custom

    PageCallbacks ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.ApacheOptsShow_${MUI_UNIQUEID} ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.ApacheOptsLeave_${MUI_UNIQUEID}

    Caption " "

  PageExEnd

  !insertmacro MUI_FUNCTION_APACHEOPTSPAGE ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.ApacheOptsShow_${MUI_UNIQUEID} ${MUI_PAGE_UNINSTALLER_FUNCPREFIX}mui.ApacheOptsLeave_${MUI_UNIQUEID}

!macroend

!macro MUI_PAGE_APACHEOPTS

  !verbose push
  !verbose ${MUI_VERBOSE}

  !insertmacro MUI_PAGE_INIT
  !insertmacro MUI_PAGEDECLARATION_APACHEOPTS

  !verbose pop

!macroend

!macro MUI_UNPAGE_APACHEOPTS

  !verbose push
  !verbose ${MUI_VERBOSE}

  !insertmacro MUI_UNPAGE_INIT
  !insertmacro MUI_PAGEDECLARATION_APACHEOPTS

  !verbose pop

!macroend


;--------------------------------
;Page functions

!macro MUI_FUNCTION_APACHEOPTSPAGE SHOW LEAVE

  Function "${SHOW}"

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM PRE
    !insertmacro MUI_HEADER_TEXT_PAGE "$(^ApachePageTitle)" "$(^ApachePageDesc)"

    nsDialogs::Create 1018
    Pop $mui.ApacheOptsPage

    ${If} $mui.ApacheOptsPage == error
        Abort
    ${EndIf}

    ${NSD_CreateLabel} 0 0 100% 12u "$(^ApachePageOptsServerName)"
    Pop $mui.ApacheOptsPage.ServerName.LBL

    ${NSD_CreateText} 0 13u 50% 12u $mui.ApacheOptsPage.ServerName.VAL
    Pop $mui.ApacheOptsPage.ServerName.TXT
    ${NSD_OnChange} $mui.ApacheOptsPage.ServerName.TXT mui.ApacheOptsPage.CheckEmptyOrNot

    ${NSD_CreateLabel} 0 33u 100% 12u "$(^ApachePageOptsAdminEmail)"
    Pop $mui.ApacheOptsPage.AdminEmail.LBL

    ${NSD_CreateText} 0 46u 50% 12u $mui.ApacheOptsPage.AdminEmail.VAL
    Pop $mui.ApacheOptsPage.AdminEmail.TXT
    ${NSD_OnChange} $mui.ApacheOptsPage.AdminEmail.TXT mui.ApacheOptsPage.CheckEmptyOrNot

    ${NSD_CreateLabel} 0 66u 100% 12u "$(^ApachePageOptsServerPortHTTP)"
    Pop $mui.ApacheOptsPage.ServerPortHTTP.LBL

    ${NSD_CreateNumber} 0 79u 10% 12u $mui.ApacheOptsPage.ServerPortHTTP.VAL
    Pop $mui.ApacheOptsPage.ServerPortHTTP.TXT
    ${NSD_OnChange} $mui.ApacheOptsPage.ServerPortHTTP.TXT mui.ApacheOptsPage.CheckEmptyOrNot

    ${NSD_CreateLabel} 20% 66u 100% 12u "$(^ApachePageOptsServerPortHTTPS)"
    Pop $mui.ApacheOptsPage.ServerPortHTTPS.LBL

    ${NSD_CreateNumber} 20% 79u 10% 12u $mui.ApacheOptsPage.ServerPortHTTPS.VAL
    Pop $mui.ApacheOptsPage.ServerPortHTTPS.TXT
    ${NSD_OnChange} $mui.ApacheOptsPage.ServerPortHTTPS.TXT mui.ApacheOptsPage.CheckEmptyOrNot

    ${If} $mui.ApacheOptsPage.ServerName.VAL == ""
    ${OrIf} $mui.ApacheOptsPage.AdminEmail.VAL == ""
    ${OrIf} $mui.ApacheOptsPage.ServerPortHTTP.VAL == ""
    ${OrIf} $mui.ApacheOptsPage.ServerPortHTTPS.VAL == ""
        GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
        EnableWindow $3 0           ; disable next/install button
    ${EndIf}

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM SHOW

    nsDialogs::Show

  FunctionEnd

  Function "${LEAVE}"

    Call mui.ApacheOptsPage.CheckEmptyOrNot

    !insertmacro MUI_PAGE_FUNCTION_CUSTOM LEAVE

  FunctionEnd

!macroend


Function mui.ApacheOptsPage.UpdateState

    ${NSD_GetText}  $mui.ApacheOptsPage.ServerName.TXT   $mui.ApacheOptsPage.ServerName.VAL
    ${NSD_GetText}  $mui.ApacheOptsPage.AdminEmail.TXT   $mui.ApacheOptsPage.AdminEmail.VAL
    ${NSD_GetText}  $mui.ApacheOptsPage.ServerPortHTTP.TXT   $mui.ApacheOptsPage.ServerPortHTTP.VAL
    ${NSD_GetText}  $mui.ApacheOptsPage.ServerPortHTTPS.TXT   $mui.ApacheOptsPage.ServerPortHTTPS.VAL

    ${str_trim} $mui.ApacheOptsPage.ServerName.VAL  $mui.ApacheOptsPage.ServerName.VAL
    ${str_trim} $mui.ApacheOptsPage.AdminEmail.VAL  $mui.ApacheOptsPage.AdminEmail.VAL

FunctionEnd

Function mui.ApacheOptsPage.CheckEmptyOrNot

    Call mui.ApacheOptsPage.UpdateState

    StrCmp $mui.ApacheOptsPage.ServerName.VAL "" haltproc
    StrCmp $mui.ApacheOptsPage.AdminEmail.VAL "" haltproc
    StrCmp $mui.ApacheOptsPage.ServerPortHTTP.VAL "" haltproc
    StrCmp $mui.ApacheOptsPage.ServerPortHTTPS.VAL "" haltproc
    Goto proceed

    haltproc:
    GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
    EnableWindow $3 0           ; disable next/install button
    Abort                       ; Return to the page

    proceed:
    GetDlgItem $3 $HWNDPARENT 1 ; Catch next/install button
    EnableWindow $3 1           ; enable next/install button

FunctionEnd
