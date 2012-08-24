Function GetRoot
  Exch $0
  Push $1
  Push $2
  Push $3
  Push $4
 
  StrCpy $1 $0 2
  StrCmp $1 "\\" UNC
    StrCpy $0 $1
    Goto done
 
UNC:
  StrCpy $2 3
  StrLen $3 $0
  loop:
    IntCmp $2 $3 "" "" loopend
    StrCpy $1 $0 1 $2
    IntOp $2 $2 + 1
    StrCmp $1 "\" loopend loop
  loopend:
    StrCmp $4 "1" +3
      StrCpy $4 1
      Goto loop
    IntOp $2 $2 - 1
    StrCpy $0 $0 $2
 
done:
  Pop $4
  Pop $3
  Pop $2
  Pop $1
  Exch $0
FunctionEnd

!define str_trim "!insertmacro macro_str_trim"
!macro macro_str_trim string trimed
    Push "${string}"
    Call str_trim
    Pop ${trimed}
!macroend

!define str_replace "!insertmacro macro_str_replace"
!macro macro_str_replace search repalce subject output
    Push "${subject}"
    Push "${search}"
    Push "${repalce}"
    Call str_replace
    Pop ${output}
!macroend

!define file_replace "!insertmacro macro_file_replace"
!macro macro_file_replace search replace start count subject
    Push "${search}"
    Push "${replace}"
    Push "${start}"
    Push "${count}"
    Push "${subject}"
    Call file_replace
!macroend

Function str_replace
    Exch $R2 # replace
    Exch 1
    Exch $R1 # search
    Exch 2
    Exch $R0 # subject
    Push $R3
    Push $R4
    Push $R5
    Push $R6
    Push $R7
    Push $R8
    Push $R9

    StrCpy $R3 0
    StrLen $R4 $R1
    StrLen $R6 $R0
    StrLen $R9 $R2

    loop:
    StrCpy $R5 $R0 $R4 $R3
    StrCmp $R5 $R1 found
    StrCmp $R3 $R6 done
    IntOp $R3 $R3 + 1 # move offset by 1 to check the next character
    Goto loop

    found:
    StrCpy $R5 $R0 $R3
    IntOp $R8 $R3 + $R4
    StrCpy $R7 $R0 "" $R8
    StrCpy $R0 $R5$R2$R7
    StrLen $R6 $R0
    IntOp $R3 $R3 + $R9 # move offset by length of the replacement string
    Goto loop

    done:
    Pop $R9
    Pop $R8
    Pop $R7
    Pop $R6
    Pop $R5
    Pop $R4
    Pop $R3
    Push $R0
    Push $R1
    Pop $R0
    Pop $R1
    Pop $R0
    Pop $R2
    Exch $R1
FunctionEnd

Function file_replace
    Exch $0 # subject - file to replace in
    Exch
    Exch $1 # count - number to replace after
    Exch
    Exch 2
    Exch $2 # start - replace and onwards
    Exch 2
    Exch 3
    Exch $3 # replace
    Exch 3
    Exch 4
    Exch $4 # search
    Exch 4
    Push $5 # minus count
    Push $6 # universal
    Push $7 # end string
    Push $8 # left string
    Push $9 # right string
    Push $R0 # file1
    Push $R1 # file2
    Push $R2 # read
    Push $R3 # universal
    Push $R4 # count (onwards)
    Push $R5 # count (after)
    Push $R6 # temp file name

    GetTempFileName $R6
    FileOpen $R1 $0 r # file to search in
    FileOpen $R0 $R6 w # temp file
    StrLen $R3 $4
    StrCpy $R4 -1
    StrCpy $R5 -1

    loop_read:
    ClearErrors
    FileRead $R1 $R2 # read line
    IfErrors exit
    StrCpy $5 0
    StrCpy $7 $R2

    loop_filter:
    IntOp $5 $5 - 1
    StrCpy $6 $7 $R3 $5 # search
    StrCmp $6 "" file_write1
    StrCmp $6 $4 0 loop_filter

    StrCpy $8 $7 $5 # left part
    IntOp $6 $5 + $R3
    IntCmp $6 0 is0 not0

    is0:
    StrCpy $9 ""
    Goto done

    not0:
    StrCpy $9 $7 "" $6 # right part

    done:
    StrCpy $7 $8$3$9 # re-join

    IntOp $R4 $R4 + 1
    StrCmp $2 all loop_filter
    StrCmp $R4 $2 0 file_write2
    IntOp $R4 $R4 - 1

    IntOp $R5 $R5 + 1
    StrCmp $1 all loop_filter
    StrCmp $R5 $1 0 file_write1
    IntOp $R5 $R5 - 1
    Goto file_write2

    file_write1:
    FileWrite $R0 $7 # write modified line
    Goto loop_read

    file_write2:
    FileWrite $R0 $R2 # write unmodified line
    Goto loop_read

    exit:
    FileClose $R0
    FileClose $R1

    SetDetailsPrint none
    Delete $0
    Rename $R6 $0
    Delete $R6
    SetDetailsPrint both

    Pop $R6
    Pop $R5
    Pop $R4
    Pop $R3
    Pop $R2
    Pop $R1
    Pop $R0
    Pop $9
    Pop $8
    Pop $7
    Pop $6
    Pop $5
    Pop $0
    Pop $1
    Pop $2
    Pop $3
    Pop $4
FunctionEnd

Function str_trim
    Exch $R1 # Original string
    Push $R2
 
    str_trim_loop:
    StrCpy $R2 "$R1" 1
    StrCmp "$R2" " " str_trim_left
    StrCmp "$R2" "$\r" str_trim_left
    StrCmp "$R2" "$\n" str_trim_left
    StrCmp "$R2" "$\t" str_trim_left
    GoTo str_trim_loop2

    str_trim_left:   
    StrCpy $R1 "$R1" "" 1
    Goto str_trim_loop
 
    str_trim_loop2:
    StrCpy $R2 "$R1" 1 -1
    StrCmp "$R2" " " str_trim_right
    StrCmp "$R2" "$\r" str_trim_right
    StrCmp "$R2" "$\n" str_trim_right
    StrCmp "$R2" "$\t" str_trim_right
    GoTo str_trim_done

    str_trim_right:  
    StrCpy $R1 "$R1" -1
    Goto str_trim_loop2
 
    str_trim_done:
    Pop $R2
    Exch $R1
FunctionEnd

Function SCQuickLaunch_Show
    ${NSD_CreateCheckbox} 120u 105u 100% 10u "$(^AddQuickLaunch)"
    Pop $SCQuickLaunch
    SetCtlColors $SCQuickLaunch "" "ffffff"
    ${NSD_Check} $SCQuickLaunch
FunctionEnd

Function SCQuickLaunch_Leave
    ${NSD_GetState} $SCQuickLaunch $0
    ${If} $0 <> 0
        SetOutPath $QUICKLAUNCH
        CreateShortcut "$QUICKLAUNCH\$(^Name).lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
    ${EndIf}
FunctionEnd