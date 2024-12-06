<x-app-layout :title="'Input Example'">

    <style>
        td{
           padding: 2px 10px !important; 
        }
        
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Timesheet</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- ----------------------------------------------------------------------------------- --}}
                    
                    <div id="rowContentInner">
                        <table class="table table-bordered">
                            <form name="timesheet" method="get" action="/interface/timesheet/ViewUserTimeSheet.php"></form>
                            <tbody>
                                <tr>
                                    <td class="tblPagingLeft" colspan="7" align="right">
                                        <br>
                                    </td>
                                </tr>
            
                                <tr class="tblHeader bg-success text-white">
                                    <td colspan="8">
                                        <span style="float:left;">
                                            &nbsp;
                                            Group:	
                                            <select name="filter_data[group_ids]" id="filter_branch" onchange="this.form.submit()">
                                                <option label="-- All --" value="-1" selected="selected">-- All --</option>
                                                <option label="Root" value="0">Root</option>
                                                <option label="|  &nbsp;SUPPORT STAFF (ACADEMIC SUPPORT )" value="11">|  &nbsp;SUPPORT STAFF (ACADEMIC SUPPORT )</option>
                                                <option label="|  &nbsp;|  &nbsp;new d" value="18">|  &nbsp;|  &nbsp;new d</option>
                                                <option label="|  &nbsp;|  &nbsp;|  &nbsp;hg" value="19">|  &nbsp;|  &nbsp;|  &nbsp;hg</option>
                                                <option label="|  &nbsp;TEACHING STAFF" value="2">|  &nbsp;TEACHING STAFF</option>
                                                <option label="|  &nbsp;TEMPORARY (ACADEMIC SUPPORT ) STAFF" value="3">|  &nbsp;TEMPORARY (ACADEMIC SUPPORT ) STAFF</option>
                                                <option label="|  &nbsp;ADMINISTRATIVE STAFF" value="4">|  &nbsp;ADMINISTRATIVE STAFF</option>
                                                <option label="|  &nbsp;SUPPORT STAFF (CLERICAL &amp; ALLIED GRADES )" value="5">|  &nbsp;SUPPORT STAFF (CLERICAL &amp; ALLIED GRADES )</option>
                                                <option label="|  &nbsp;LIBRARY STAFF" value="6">|  &nbsp;LIBRARY STAFF</option>
                                                <option label="|  &nbsp;SUPPORT STAFF (MINOR GRADES)" value="7">|  &nbsp;SUPPORT STAFF (MINOR GRADES)</option>
                                                <option label="|  &nbsp;TEMPORARY STAFF" value="10">|  &nbsp;TEMPORARY STAFF</option>
                                                <option label="|  &nbsp;test" value="14">|  &nbsp;test</option>
                                                <option label="|  &nbsp;Finance Staff" value="15">|  &nbsp;Finance Staff</option>
                                                <option label="|  &nbsp;|  &nbsp;Accounts &amp; Finance Staff" value="16">|  &nbsp;|  &nbsp;Accounts &amp; Finance Staff</option>
                                                <option label="|  &nbsp;Security Guard" value="17">|  &nbsp;Security Guard</option>
                                            </select>
                                            
                                            Branch:								
                                            <select name="filter_data[branch_ids]" id="filter_branch" onchange="this.form.submit()">
                                                <option label="-- All --" value="-1" selected="selected">-- All --</option>
                                                <option label="Head Office" value="1">Head Office</option>
                                                <option label="North" value="17">North</option>
                                                <option label="Southren" value="16">Southren</option>
                                            </select>
                                            
                                            Dept:								
                                            <select name="filter_data[department_ids]" id="filter_department" onchange="this.form.submit()">
                                                <option label="-- All --" value="-1" selected="selected">-- All --</option>
                                                <option label="Accounts Division" value="25">Accounts Division</option>
                                                <option label="Conceptangle Project" value="26">Conceptangle Project</option>
                                                <option label="Law Partners" value="15">Law Partners</option>
                                                <option label="MBC Project" value="13">MBC Project</option>
                                                <option label="Research &amp; Development" value="29">Research &amp; Development</option>
                                                <option label="Sales &amp; Marketing" value="24">Sales &amp; Marketing</option>
                                                <option label="Software Development" value="12">Software Development</option>
                                            </select>
                                            <span style="white-space: nowrap;">
                                                Employee:									
                                                <a href="javascript:resetAction();navSelectBox('filter_user', 'prev');document.timesheet.submit()"><img style="vertical-align: middle" src="/interface/images//nav_prev_sm.gif"></a>
                                                <select name="filter_data[user_id]" id="filter_user" onchange="this.form.submit()">
                                                    <option label=" - John, John" value="1"> - John, John</option>
                                                    <option label="5083 - Kotiyakumbura Arachchige, Sanjaya" value="1160" selected="selected">5083 - Kotiyakumbura Arachchige, Sanjaya</option>
                                                    <option label="12345 - wick, sam" value="1162">12345 - wick, sam</option>
                                                    <option label=" - Hashantha, Hashantha" value="1161"> - Hashantha, Hashantha</option>
                                                </select>
                                                <a href="javascript:resetAction();navSelectBox('filter_user', 'next');document.timesheet.submit()"><img style="vertical-align: middle" src="/interface/images//nav_next_sm.gif"></a>
                                            </span>
                                        </span>
                                    
                                        <span style="float: right">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Add:
                                            <input type="BUTTON" class="button" name="action" value="Punch" onclick="editPunch('','',1160,1733423400)">
                                            <input type="BUTTON" class="button" name="action" value="Absence" onclick="editAbsence('',1160,1733423400)">
                                        </span>
                                    </td>
                                </tr>
                            
                                <tr class="tblHeader bg-success text-white">
                                    <td colspan="8">
                                        Date:						<a href="/interface/timesheet/ViewUserTimeSheet.php?filter_data[group_ids]=-1&amp;filter_data[branch_ids]=-1&amp;filter_data[department_ids]=-1&amp;filter_data[user_id]=1160&amp;filter_data[date]=1733423400&amp;prev_pp=1" onclick="resetAction();"><img style="vertical-align: middle" src="/interface/images//nav_first_sm.gif"></a>
                                        <a href="/interface/timesheet/ViewUserTimeSheet.php?filter_data[group_ids]=-1&amp;filter_data[branch_ids]=-1&amp;filter_data[department_ids]=-1&amp;filter_data[user_id]=1160&amp;filter_data[date]=1733423400&amp;prev_week=1" onclick="resetAction();"><img style="vertical-align: middle" src="/interface/images//nav_prev_sm.gif"></a>
                
                                        <input type="text" size="15" id="filter_date" name="filter_data[date]" value="06/12/2024" onchange="resetAction();this.form.submit()">
                                        <img src="/interface//images/cal.gif" id="cal_filter_date" width="16" height="16" border="0" alt="Pick a date" onmouseover="calendar_setup('filter_date', 'cal_filter_date', false);">
                                        <a href="/interface/timesheet/ViewUserTimeSheet.php?filter_data[group_ids]=-1&amp;filter_data[branch_ids]=-1&amp;filter_data[department_ids]=-1&amp;filter_data[user_id]=1160&amp;filter_data[date]=1733423400&amp;next_week=1" onclick="resetAction();"><img style="vertical-align: middle" src="/interface/images//nav_next_sm.gif"></a>
                                        <a href="/interface/timesheet/ViewUserTimeSheet.php?filter_data[group_ids]=-1&amp;filter_data[branch_ids]=-1&amp;filter_data[department_ids]=-1&amp;filter_data[user_id]=1160&amp;filter_data[date]=1733423400&amp;next_pp=1" onclick="resetAction();"><img style="vertical-align: middle" src="/interface/images//nav_last_sm.gif"></a>
                                    </td>
                                </tr>

                                <tr class="tblHeader bg-success text-white">
                                    <td>
                                        <a href="/interface//report/TimesheetDetail.php?action:display_report=1&amp;filter_data[print_timesheet]=1&amp;filter_data[user_id]=1160&amp;filter_data[pay_period_ids]="><img src="/interface/images/printer.gif" alt="Print Timesheet"></a>&nbsp;&nbsp;
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('02/12/2024')">
                                        Mon<br>Dec 2
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('03/12/2024')">
                                        Tue<br>Dec 3
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('04/12/2024')">
                                        Wed<br>Dec 4
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('05/12/2024')">
                                        Thu<br>Dec 5
                                    </td>
                                    <td width="12%" id="cursor-hand" bgcolor="#33CCFF" onclick="changeDate('06/12/2024')">
                                        Fri<br>Dec 6
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('07/12/2024')">
                                        Sat<br>Dec 7
                                    </td>
                                    <td width="12%" id="cursor-hand" onclick="changeDate('08/12/2024')">
                                        Sun<br>Dec 8
                                    </td>
                                </tr>
                                                
                                <tr class="tblDataWhiteNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        In
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733077800,10);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left">
                                                                                                </td>
                                                    <td width="50%" align="center" nowrap="">
                                                                                                        <br>
                                                                                                </td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733164200,10);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left">
                                                                                                </td>
                                                    <td width="50%" align="center" nowrap="">
                                                                                                        <br>
                                                                                                </td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733250600,10);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left">
                                                                                                </td>
                                                    <td width="50%" align="center" nowrap="">
                                                                                                        <br>
                                                                                                </td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733337000,10);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""> <br></td>
                                                    <td width="25%" align="right"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap="">
                                                        <a href="javascript:editPunch(306709)">8:00</a>
                                                    </td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733509800,10);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left">
                                                        <span style="float: left">
                                                            <font color="#FF0000">
                                                                <b>M2</b>
                                                            </font>
                                                        </span>
                                                                                                                                                                                                                                </td>
                                                    <td width="50%" align="center" nowrap="">
                                                        <a href="javascript:editPunch(306708)">12:00<a>																					
                                                    </td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="tblDataWhiteNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Out
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733077800,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733164200,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733250600,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733337000,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><a href="javascript:editPunch(306710)">17:00</a></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','',1160,1733509800,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="25%" align="left"></td>
                                                    <td width="50%" align="center" nowrap=""><br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="cellHL" id="cursor-hand" onclick="editPunch('','231658',1160,1733596200,20);" nowrap="">
                                        <table align="center" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                
                                                    <td width="25%" align="left">
                                                        <span style="float: left">
                                                            <font color="#FF0000"><b>M2</b></font>
                                                        </span>
                                                                                                                                                                                                                                </td>
                                                    <td width="50%" align="center" nowrap=""> <br></td>
                                                    <td width="25%" align="right">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="tblDataGreyNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Tea Break
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><font color="red">00:15</font></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="tblDataWhiteNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Breakfast
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><font color="red">00:30</font></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="tblDataGreyNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Exceptions
                                    </td>
                                    <td><b></b></td>
                                    <td><b></b></td>
                                    <td><b></b></td>
                                    <td><b></b></td>
                                    <td><b></b></td>
                                    <td><b></b></td>
                                    <td><b><font color="#FF0000">M2</font></b></td>
                                </tr>
                                <tr class="tblHeader bg-success text-white">
                                    <td colspan="8"> Accumulated Time</td>
                                </tr>
                                <tr class="tblDataWhiteNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Total Time
                                    </td>
                                    <td><a href="javascript:hourList('','1160','1733077800')">00:00</a></td>
                                    <td><a href="javascript:hourList('','1160','1733164200')">00:00</a></td>
                                    <td><a href="javascript:hourList('','1160','1733250600')">00:00</a></td>
                                    <td><a href="javascript:hourList('','1160','1733337000')">00:00</a></td>
                                    <td class="cellHL"><a href="javascript:hourList('','1160','1733423400')">11:15</a></td>
                                    <td><a href="javascript:hourList('','1160','1733509800')">00:00</a></td>
                                    <td class="cellHL"><a href="javascript:hourList('','1160','1733596200')">00:00</a></td>
                                </tr>
                                <tr class="tblDataGreyNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Daily OT 3
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>00:45</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="tblHeader bg-success text-white">
                                    <td colspan="100">Branch</td>
                                </tr>
                                <tr class="tblDataWhiteNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Head Office
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>00:45</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="tblHeader bg-success text-white">
                                <td colspan="100"> Premium</td>
                                </tr>
                                <tr class="tblDataGreyNH">
                                    <td class="tblHeader bg-success text-white" style="font-weight: bold; text-align: right">
                                        Attendance  Allowance Rs 120
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>04:00</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="tblHeader bg-success text-white">
                                    <td colspan="8">Pay Period:	NONE</td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="2">               
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr class="tblHeader bg-success text-white">
                                                    <td colspan="2">Exception Legend</td>
                                                </tr>
                
                                                <tr class="tblHeader bg-success text-white">
                                                    <td>Code</td>
                                                    <td>Exception</td>
                                                </tr>
                                                                                
                                                <tr class="tblDataWhiteNH">
                                                    <td>
                                                        <font color="#FF0000">
                                                            <b>M2</b>
                                                        </font>
                                                    </td>
                                                    <td>
                                                        Missing Out Punch
                                                    </td>
                                                </tr>
                                                </tbody>
                                        </table>
                                    </td>
                                    <td colspan="3">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr class="tblHeader bg-success text-white">
                                                    <td colspan="2">\Paid Time\</td>
                                                </tr>
                                                <tr class="tblDataGreyNH" nowrap="">
                                                    <td>Worked Time</td>
                                                    <td>92:05</td>
                                                </tr>               
                                                <tr class="tblDataWhiteNH" style="font-weight: bold;" nowrap="">
                                                    <td>Total Time</td>
                                                    <td width="75">92:05</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                        <td colspan="3">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr class="tblHeader bg-success text-white">
                                                        <td colspan="2">Accumulated Time</td>
                                                    </tr>                      
                                                    <tr class="tblDataGreyNH">
                                                        <td>Regular Time</td>
                                                        <td>50:55</td>
                                                    </tr>
                                                                                
                                                    <tr class="tblDataWhiteNH">
                                                        <td>Daily OT</td>
                                                        <td>03:55</td>
                                                    </tr>
                                                                                
                                                    <tr class="tblDataGreyNH">
                                                        <td>Daily OT 3</td>
                                                        <td>03:30</td>
                                                    </tr>
                                                
                                                
                                                    <tr class="tblDataWhiteNH" style="font-weight: bold;">
                                                        <td>Total Time</td>
                                                        <td>92:05</td>
                                                    </tr>
                    
                                                    <tr>
                                                        <td colspan="2" align="center">
                                                            <select name="action_option" id="select_action">
                                                                <option label="-- Select Action --" value="0">-- Select Action --</option>
                                                                <option label="Recalculate Employee" value="recalculate_employee">Recalculate Employee</option>
                                                                <option label="Recalculate Company" value="recalculate_company">Recalculate Company</option>
                                                                <option label="Recalculate Mid Pay" value="recalculate_mid_pay">Recalculate Mid Pay</option>
                                                                <option label="Recalculate FInal Pay" value="recalculate_pay_stub">Recalculate FInal Pay</option>
                                                            </select>
                                                            <input type="SUBMIT" class="button" name="action:submit" value="Submit" onclick="return confirmAction();">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                    
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>

                    {{-- ----------------------------------------------------------------------------------- --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>