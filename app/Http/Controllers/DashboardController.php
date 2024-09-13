<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('load')) {
            $results = DB::select("
        Select SO_ID, AR_NAMES, MKT_OWNER, DLT, DLT_TP, DATEDIFF(DLT_TP, now()) as DiffDay,  completion_STK, completion_PRD, TotalSOQty, TotalSTKQty, TotalPRDQty ,
       if (DATEDIFF(DLT_TP, now()) <= 5 , if(TotalPRDQty <> 0, totalhrs , '-') , '-' ) as OUTSTANDING_PROCESS_TIME
from
(
    select  SO_HDR.SO_ID, SO_HDR.SO_AR,
            AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
            AR_MST_CUSTOMER.AR_CAT1,
            (select MTN_DESC from ierpSM.MTN_MST where CLASS_ID = 'AR_CAT1' and MTN_ID = AR_MST_CUSTOMER.AR_CAT1  ) as ARCAT1,
            SO_HDR.SO_DATE, SO_HDR.SO_MKT_OWNER,
            (select MTN_MKT_OWNER.MKT_OWNER_DESC from ierpSM.MTN_MKT_OWNER
            where MTN_MKT_OWNER.MKT_OWNER_ID = SO_HDR.SO_MKT_OWNER) as MKT_OWNER,
            sum(SO_DT.SO_QTY) as TotalSOQty, sum(SO_DT.SO_STK_QTY) as TotalSTKQty, sum(SO_DT.SO_PRD_QTY) as TotalPRDQty,
            ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD,
            ifnull(sum(Tbl_whqrmaster_STK.pbag_stk),0) as Done_pbag_STK,
            TBLDLT.DLT,
            DATE_ADD(TBLDLT.DLT, INTERVAL -2 DAY) as DLT_TP,
            ifnull(((ifnull(sum(Tbl_whqrmaster_STK.pbag_stk),0) / sum(SO_DT.SO_STK_QTY)) * 100),0) as completion_STK,
            ifnull(((ifnull(sum(Tbl_whqrmaster.pbag),0) / sum(SO_DT.SO_PRD_QTY)) * 100),0) as completion_PRD,
            DATEDIFF(TBLDLT.DLT, SO_HDR.SO_DATE) as DIFF_SO_DLT,
            sum(TblOutstandingProcessTime.totalhrs) as totalhrs

    from  ierpSM.AR_MST_CUSTOMER, ierpSM.SO_HDR SO_HDR,
          (select distinct SO_HDR_ID,  max(SO_SEQ_DLT) as DLT from ierpSM.SO_DT_DLT where deleted_at is null group by SO_HDR_ID)  TBLDLT  ,
          ierpSM.SO_DT SO_DT

    left join ( select sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , sum(pbag) as pbag, 0 as looseitem
                from ierpadmin.qrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                and substring(sonum, 1, 2) = 'SO'
                and status = 'wh'
                group by sonum, stockcode )
    Tbl_whqrmaster
    on SO_DT.SO_ID = Tbl_whqrmaster.sonum
    and SO_DT.SO_STK_CODE = Tbl_whqrmaster.stockcode

    left join ( select sonum, stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev_stk , sum(pbag) as pbag_stk, sum(looseitem) as looseitem_stk
                from ierpadmin.whqrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
		            and substring(sonum, 1, 2) = 'SO'
                and whrev is null
                and shipmark is not null
                and statusInfoList = 'C'
                group by sonum, stockcode)
    Tbl_whqrmaster_STK
    on SO_DT.SO_ID = Tbl_whqrmaster_STK.sonum
    and SO_DT.SO_STK_CODE = Tbl_whqrmaster_STK.stockcode

    left join
    (
        select distinct TblNonComplete.SO_ID, TblNonComplete.SO_STK_CODE, TblNonComplete.TotalPRDQty, TblNonComplete.Done_pbag_PRD,
               round(((((TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD) /  ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)))
               * ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME)) / 3600),0) as totalhrs,
               ifnull(TblPlanning.PS_BOOK_MACHINE_ID, TblStandardBOM.STK_MACHINE) as machine,
               ifnull(TblPlanning.PS_BOOK_MOULD_ID, TblStandardBOM.STK_MOULD) as mould,
               ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)) as Cavity ,
               ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME) as CycleTime
        from
        (
            select  distinct SO_HDR.SO_ID, SO_HDR.SO_AR, SO_DT.SO_STK_CODE,
                    sum(SO_DT.SO_QTY) as TotalSOQty, sum(SO_DT.SO_STK_QTY) as TotalSTKQty, sum(SO_DT.SO_PRD_QTY) as TotalPRDQty,
                    ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD

            from  ierpSM.SO_HDR SO_HDR,
                  ierpSM.SO_DT SO_DT

            left join (select wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                       from (

                            select (select warehouse_id from device where device.deviceId = qrmaster.deviceId) as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, 0 as looseitem
                            from ierpadmin.qrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'SO'
                            and status = 'wh'
                            group by qrmaster.deviceId, sonum, stockcode, whackwrev_by

                            union all

                            select whrev as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                            from ierpadmin.whqrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB( now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'SO'
                            and whrev is null
                            and  statusInfoList = 'C'
                            and shipmark is not null
                            group by whrev,  sonum, stockcode,  whackwrev_by

                      ) TblA
                    group by wh_from, sonum, stockcode,  whackwrev_by)
            Tbl_whqrmaster
            on SO_DT.SO_ID = Tbl_whqrmaster.sonum
            and SO_DT.SO_STK_CODE = Tbl_whqrmaster.stockcode

            where 1=1

            and SO_HDR.CO_ID = SO_DT.CO_ID
            and SO_HDR.SO_ID = SO_DT.SO_ID
            and SO_HDR.SO_STATUS not in ('D', 'C')
            and SO_DT.SO_SEQ_STATUS not in ('D', 'C')
            and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')

            group by  SO_HDR.SO_ID, SO_HDR.SO_AR, SO_DT.SO_STK_CODE

        ) TblNonComplete
        left join
        (
            select distinct PS_HDR.PS_BOOK_SOURCE_ID, PS_BOOK_DT.PS_BOOK_ID, PS_BOOK_DT.PS_BOOK_MACHINE_ID, PS_BOOK_DT.PS_BOOK_MOULD_ID, md.StandardMouldCavity, md.StandardCycleTime,
                   PS_BOOK_DT.PS_BOOK_BOM_ID, PS_BOOK_DT.PS_BOOK_ROUTE_ID,
                   PS_BOOK_DT.PS_BOOK_WC, PS_BOOK_DT.PS_BOOK_DATETIME, PS_BOOK_DT.PS_BOOK_DATETIME2, PS_BOOK_DT.PS_BOOK_QTY, PS_BOOK_DT.PS_BOOK_TOTAL_QTY,
                   PS_BOOK_DT.PS_BOOK_STK_FG
            from ierpPM.PS_BOOK_DT, ierpadmin.mould_details md, (select distinct PS_BOOK_ID,  PS_BOOK_SOURCE_ID
                                                                 from ierpPM.PS_BOOK_HDR
                                                                 where PS_BOOK_SOURCE_ID in (select distinct  SO_HDR.SO_ID
                                                                                              from  ierpSM.SO_HDR SO_HDR, ierpSM.SO_DT SO_DT
                                                                                              where SO_HDR.CO_ID = SO_DT.CO_ID
                                                                                              and SO_HDR.SO_ID = SO_DT.SO_ID
                                                                                              and SO_HDR.SO_STATUS not in ('D', 'C')
                                                                                              and SO_DT.SO_SEQ_STATUS not in ('D', 'C')
                                                                                              and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')  )
                                                                ) as PS_HDR
            where PS_BOOK_DT.PS_BOOK_MOULD_ID =  md.mouldNo  COLLATE utf8_general_ci
            and PS_BOOK_DT.PS_BOOk_STATUS <> 'D' and PS_BOOK_DT.deleted_at is null
            and substring(PS_BOOK_DT.PS_BOOK_STK_FG,1,1) not in ('C', 'W')
            and PS_BOOK_DT.PS_BOOK_ID = PS_HDR.PS_BOOK_ID
        ) TblPlanning
        on TblNonComplete.SO_STK_CODE = TblPlanning.PS_BOOK_STK_FG
		and TblNonComplete.SO_ID = TblPlanning.PS_BOOK_SOURCE_ID

        left join
        (
           select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct PRD_BOM_DT.BOM_FG, PRD_BOM_DT.BOM_RAW_STKCODE
            from ierpPM.PRD_BOM_DT
            where PRD_BOM_DT.BOM_RAW_STATUS = 'A'
            and substring(PRD_BOM_DT.BOM_RAW_STKCODE,1,1) = 'W'
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_RAW_STKCODE
          and STK_STD_SETTING.STK_DEFAULT = 'S'

          union all

          select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct BOM_FG, BOM_STD_MACHINE, BOM_STD_MOULD
            from ierpPM.PRD_BOM_HDR
            where BOM_STATUS = 'Y'
            and BOM_STD_MACHINE <> 'MANUAL'
            and substring(BOM_FG,1,1) not in ('W','C')
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_FG
          and STK_STD_SETTING.STK_DEFAULT = 'S'
        ) TblStandardBOM
        on TblStandardBOM.BOM_FG = TblNonComplete.SO_STK_CODE

        where TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD <> 0
    ) TblOutstandingProcessTime
    on TblOutstandingProcessTime.SO_ID = SO_DT.SO_ID
    and TblOutstandingProcessTime.SO_STK_CODE = SO_DT.SO_STK_CODE

    where 1=1

    and SO_HDR.SO_AR = AR_MST_CUSTOMER.AR_ID
    and SO_HDR.CO_ID = SO_DT.CO_ID
    and SO_HDR.SO_ID = SO_DT.SO_ID
    and SO_HDR.SO_ID = TBLDLT.SO_HDR_ID
    and SO_HDR.SO_STATUS not in ('D', 'C')
    and SO_DT.SO_SEQ_STATUS not in ('D')
    and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')
    -- and SO_HDR.SO_ID = 'SO23/00813'

    group by  SO_HDR.SO_ID, SO_HDR.SO_AR,
              AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
              AR_MST_CUSTOMER.AR_CAT1,
              SO_HDR.SO_DATE, SO_HDR.SO_MKT_OWNER,
              TBLDLT.DLT
) TblDashboard
where TotalSOQty <> (Done_pbag_PRD + Done_pbag_STK)

union all

Select MO_ID, AR_NAMES, MKT_OWNER, DLT, DLT_TP, DATEDIFF(DLT_TP, now()) as DiffDay,  completion_STK, completion_PRD, TotalSOQty, TotalSTKQty, TotalPRDQty,
       IF(DLT = null,'-',if (DATEDIFF(DLT_TP, now()) <= 5 , if(TotalPRDQty <> 0, totalhrs , '-') , '-' )) as OUTSTANDING_PROCESS_TIME
from
(
    select  MO_HDR.MO_ID, MO_HDR.MO_AR,
            AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
            AR_MST_CUSTOMER.AR_CAT1,
            (select MTN_DESC from ierpSM.MTN_MST where CLASS_ID = 'AR_CAT1' and MTN_ID = AR_MST_CUSTOMER.AR_CAT1  ) as ARCAT1,
            MO_HDR.MO_DATE,
            AR_MST_CUSTOMER.AR_MKT_OWNER,
            (select MTN_MKT_OWNER.MKT_OWNER_DESC from ierpSM.MTN_MKT_OWNER
             where MTN_MKT_OWNER.MKT_OWNER_ID = AR_MST_CUSTOMER.AR_MKT_OWNER) as MKT_OWNER,
            sum(MO_DT.MO_QTY) as TotalSOQty, 0 as TotalSTKQty, sum(MO_DT.MO_PRD_QTY) as TotalPRDQty,
            ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD,
            0 as Done_pbag_STK,
            TBLDLT.DLT,
            DATE_ADD(TBLDLT.DLT, INTERVAL -2 DAY) as DLT_TP,
            0 as completion_STK,
            ifnull(((ifnull(sum(Tbl_whqrmaster.pbag),0) / sum(MO_DT.MO_PRD_QTY)) * 100),0) as completion_PRD,
            DATEDIFF(TBLDLT.DLT, MO_HDR.MO_DATE) as DIFF_MO_DLT,
            sum(TblOutstandingProcessTime.totalhrs) as totalhrs

    from  ierpSM.AR_MST_CUSTOMER, ierpSM.MO_HDR MO_HDR,
          (select distinct MO_ID,  max(MO_SEQ_DLT) as DLT from ierpSM.MO_DT where deleted_at is null group by MO_ID)  TBLDLT  ,
          ierpSM.MO_DT MO_DT

    left join ( select sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , sum(pbag) as pbag, 0 as looseitem
                from ierpadmin.qrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                and substring(sonum, 1, 2) = 'MO'
                and status = 'wh'
                group by sonum, stockcode )
    Tbl_whqrmaster
    on MO_DT.MO_ID = Tbl_whqrmaster.sonum
    and MO_DT.MO_STK_CODE = Tbl_whqrmaster.stockcode

    left join
    (
        select distinct TblNonComplete.MO_ID, TblNonComplete.MO_STK_CODE as MO_STK_CODE, TblNonComplete.TotalPRDQty, TblNonComplete.Done_pbag_PRD,
               round(((((TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD) /  ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)))
               * ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME)) / 3600),0) as totalhrs,
               ifnull(TblPlanning.PS_BOOK_MACHINE_ID, TblStandardBOM.STK_MACHINE) as machine,
               ifnull(TblPlanning.PS_BOOK_MOULD_ID, TblStandardBOM.STK_MOULD) as mould,
               ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)) as Cavity ,
               ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME) as CycleTime
        from
        (
            select distinct MO_HDR.MO_ID, MO_HDR.MO_AR, MO_DT.MO_STK_CODE,
                    sum(MO_DT.MO_QTY) as TotalSOQty, 0 as TotalSTKQty, sum(MO_DT.MO_PRD_QTY) as TotalPRDQty,
                    ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD

            from  ierpSM.MO_HDR MO_HDR,
                  ierpSM.MO_DT MO_DT

            left join (select wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                       from (

                            select (select warehouse_id from device where device.deviceId = qrmaster.deviceId) as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, 0 as looseitem
                            from ierpadmin.qrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'MO'
                            and status = 'wh'
                            group by qrmaster.deviceId, sonum, stockcode, whackwrev_by


                      ) TblA
                    group by wh_from, sonum, stockcode,  whackwrev_by)
            Tbl_whqrmaster
            on MO_DT.MO_ID = Tbl_whqrmaster.sonum
            and MO_DT.MO_STK_CODE = Tbl_whqrmaster.stockcode

            where 1=1

            and MO_HDR.CO_ID = MO_DT.CO_ID
            and MO_HDR.MO_ID = MO_DT.MO_ID
            and MO_HDR.MO_STATUS not in ('D', 'C')
            and MO_DT.MO_SEQ_STATUS not in ('D', 'C')
            and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y')
            group by  MO_HDR.MO_ID, MO_HDR.MO_AR, MO_DT.MO_STK_CODE

        ) TblNonComplete
        left join
        (
            select distinct PS_HDR.PS_BOOK_SOURCE_ID, PS_BOOK_DT.PS_BOOK_ID, PS_BOOK_DT.PS_BOOK_MACHINE_ID, PS_BOOK_DT.PS_BOOK_MOULD_ID, md.StandardMouldCavity, md.StandardCycleTime,
                   PS_BOOK_DT.PS_BOOK_BOM_ID, PS_BOOK_DT.PS_BOOK_ROUTE_ID,
                   PS_BOOK_DT.PS_BOOK_WC, PS_BOOK_DT.PS_BOOK_DATETIME, PS_BOOK_DT.PS_BOOK_DATETIME2, PS_BOOK_DT.PS_BOOK_QTY, PS_BOOK_DT.PS_BOOK_TOTAL_QTY,
                   PS_BOOK_DT.PS_BOOK_STK_FG
            from ierpPM.PS_BOOK_DT, ierpadmin.mould_details md, (select distinct PS_BOOK_ID,  PS_BOOK_SOURCE_ID
                                           from ierpPM.PS_BOOK_HDR
                                           where PS_BOOK_SOURCE_ID in (select distinct  MO_HDR.MO_ID
                                                                        from  ierpSM.MO_HDR MO_HDR,
                                                                              ierpSM.MO_DT MO_DT
                                                                        where MO_HDR.CO_ID = MO_DT.CO_ID
                                                                        and MO_HDR.MO_ID = MO_DT.MO_ID
                                                                        and MO_HDR.MO_STATUS not in ('D', 'C')
                                                                        and MO_DT.MO_SEQ_STATUS not in ('D', 'C')
                                                                        and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y') )
                                          ) as PS_HDR
            where PS_BOOK_DT.PS_BOOK_MOULD_ID =  md.mouldNo  COLLATE utf8_general_ci
            and PS_BOOK_DT.PS_BOOk_STATUS <> 'D' and PS_BOOK_DT.deleted_at is null
            and PS_BOOK_DT.PS_BOOK_ID = PS_HDR.PS_BOOK_ID
        ) TblPlanning
        on TblNonComplete.MO_STK_CODE = TblPlanning.PS_BOOK_STK_FG
        and TblNonComplete.MO_ID = TblPlanning.PS_BOOK_SOURCE_ID

        left join
        (
           select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct PRD_BOM_DT.BOM_FG, PRD_BOM_DT.BOM_RAW_STKCODE
            from ierpPM.PRD_BOM_DT
            where PRD_BOM_DT.BOM_RAW_STATUS = 'A'
            and substring(PRD_BOM_DT.BOM_RAW_STKCODE,1,1) = 'W'
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_RAW_STKCODE
          and STK_STD_SETTING.STK_DEFAULT = 'S'

          union all

          select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct BOM_FG, BOM_STD_MACHINE, BOM_STD_MOULD
            from ierpPM.PRD_BOM_HDR
            where BOM_STATUS = 'Y'
            and BOM_STD_MACHINE <> 'MANUAL'
            and substring(BOM_FG,1,1) not in ('C')
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_FG
          and STK_STD_SETTING.STK_DEFAULT = 'S'
        ) TblStandardBOM
        on TblStandardBOM.BOM_FG = TblNonComplete.MO_STK_CODE

        where TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD <> 0
    ) TblOutstandingProcessTime
    on TblOutstandingProcessTime.MO_ID = MO_DT.MO_ID
    and TblOutstandingProcessTime.MO_STK_CODE = MO_DT.MO_STK_CODE

    where 1=1

    and MO_HDR.MO_AR = AR_MST_CUSTOMER.AR_ID
    and MO_HDR.CO_ID = MO_DT.CO_ID
    and MO_HDR.MO_ID = MO_DT.MO_ID
    and MO_HDR.MO_ID = TBLDLT.MO_ID
    and MO_HDR.MO_STATUS not in ('D', 'C')
    and MO_DT.MO_SEQ_STATUS not in ('D')
    and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y')
    group by  MO_HDR.MO_ID, MO_HDR.MO_AR,
              AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
              AR_MST_CUSTOMER.AR_CAT1,
              MO_HDR.MO_DATE, AR_MST_CUSTOMER.AR_MKT_OWNER,
              TBLDLT.DLT
) TblDashboard
where TotalSOQty <> (Done_pbag_PRD + Done_pbag_STK)
    ");
        } else {
            $results = DB::select("
      Select SO_ID, AR_NAMES, MKT_OWNER, DLT, DLT_TP, DATEDIFF(DLT_TP, now()) as DiffDay,  completion_STK, completion_PRD, TotalSOQty, TotalSTKQty, TotalPRDQty ,
       if (DATEDIFF(DLT_TP, now()) <= 5 , if(TotalPRDQty <> 0, totalhrs , '-') , '-' ) as OUTSTANDING_PROCESS_TIME
from
(
    select  SO_HDR.SO_ID, SO_HDR.SO_AR,
            AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
            AR_MST_CUSTOMER.AR_CAT1,
            (select MTN_DESC from ierpSM.MTN_MST where CLASS_ID = 'AR_CAT1' and MTN_ID = AR_MST_CUSTOMER.AR_CAT1  ) as ARCAT1,
            SO_HDR.SO_DATE, SO_HDR.SO_MKT_OWNER,
            (select MTN_MKT_OWNER.MKT_OWNER_DESC from ierpSM.MTN_MKT_OWNER
            where MTN_MKT_OWNER.MKT_OWNER_ID = SO_HDR.SO_MKT_OWNER) as MKT_OWNER,
            sum(SO_DT.SO_QTY) as TotalSOQty, sum(SO_DT.SO_STK_QTY) as TotalSTKQty, sum(SO_DT.SO_PRD_QTY) as TotalPRDQty,
            ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD,
            ifnull(sum(Tbl_whqrmaster_STK.pbag_stk),0) as Done_pbag_STK,
            TBLDLT.DLT,
            DATE_ADD(TBLDLT.DLT, INTERVAL -2 DAY) as DLT_TP,
            ifnull(((ifnull(sum(Tbl_whqrmaster_STK.pbag_stk),0) / sum(SO_DT.SO_STK_QTY)) * 100),0) as completion_STK,
            ifnull(((ifnull(sum(Tbl_whqrmaster.pbag),0) / sum(SO_DT.SO_PRD_QTY)) * 100),0) as completion_PRD,
            DATEDIFF(TBLDLT.DLT, SO_HDR.SO_DATE) as DIFF_SO_DLT,
            sum(TblOutstandingProcessTime.totalhrs) as totalhrs

    from  ierpSM.AR_MST_CUSTOMER, ierpSM.SO_HDR SO_HDR,
          (select distinct SO_HDR_ID,  max(SO_SEQ_DLT) as DLT from ierpSM.SO_DT_DLT where deleted_at is null group by SO_HDR_ID)  TBLDLT  ,
          ierpSM.SO_DT SO_DT

    left join ( select sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , sum(pbag) as pbag, 0 as looseitem
                from ierpadmin.qrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                and substring(sonum, 1, 2) = 'SO'
                and status = 'wh'
                group by sonum, stockcode )
    Tbl_whqrmaster
    on SO_DT.SO_ID = Tbl_whqrmaster.sonum
    and SO_DT.SO_STK_CODE = Tbl_whqrmaster.stockcode

    left join ( select sonum, stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev_stk , sum(pbag) as pbag_stk, sum(looseitem) as looseitem_stk
                from ierpadmin.whqrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
		            and substring(sonum, 1, 2) = 'SO'
                and whrev is null
                and shipmark is not null
                and statusInfoList = 'C'
                group by sonum, stockcode)
    Tbl_whqrmaster_STK
    on SO_DT.SO_ID = Tbl_whqrmaster_STK.sonum
    and SO_DT.SO_STK_CODE = Tbl_whqrmaster_STK.stockcode

    left join
    (
        select distinct TblNonComplete.SO_ID, TblNonComplete.SO_STK_CODE, TblNonComplete.TotalPRDQty, TblNonComplete.Done_pbag_PRD,
               round(((((TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD) /  ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)))
               * ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME)) / 3600),0) as totalhrs,
               ifnull(TblPlanning.PS_BOOK_MACHINE_ID, TblStandardBOM.STK_MACHINE) as machine,
               ifnull(TblPlanning.PS_BOOK_MOULD_ID, TblStandardBOM.STK_MOULD) as mould,
               ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)) as Cavity ,
               ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME) as CycleTime
        from
        (
            select distinct SO_HDR.SO_ID, SO_HDR.SO_AR, SO_DT.SO_STK_CODE,
                    sum(SO_DT.SO_QTY) as TotalSOQty, sum(SO_DT.SO_STK_QTY) as TotalSTKQty, sum(SO_DT.SO_PRD_QTY) as TotalPRDQty,
                    ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD

            from  ierpSM.SO_HDR SO_HDR,
                  ierpSM.SO_DT SO_DT

            left join (select wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                       from (

                            select (select warehouse_id from device where device.deviceId = qrmaster.deviceId) as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, 0 as looseitem
                            from ierpadmin.qrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'SO'
                            and status = 'wh'
                            group by qrmaster.deviceId, sonum, stockcode, whackwrev_by

                            union all

                            select whrev as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                            from ierpadmin.whqrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB( now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'SO'
                            and whrev is null
                            and  statusInfoList = 'C'
                            and shipmark is not null
                            group by whrev,  sonum, stockcode,  whackwrev_by

                      ) TblA
                    group by wh_from, sonum, stockcode,  whackwrev_by)
            Tbl_whqrmaster
            on SO_DT.SO_ID = Tbl_whqrmaster.sonum
            and SO_DT.SO_STK_CODE = Tbl_whqrmaster.stockcode

            where 1=1

            and SO_HDR.CO_ID = SO_DT.CO_ID
            and SO_HDR.SO_ID = SO_DT.SO_ID
            and SO_HDR.SO_STATUS not in ('D', 'C')
            and SO_DT.SO_SEQ_STATUS not in ('D', 'C')
            and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')

            group by  SO_HDR.SO_ID, SO_HDR.SO_AR, SO_DT.SO_STK_CODE

        ) TblNonComplete
        left join
        (
            select distinct PS_HDR.PS_BOOK_SOURCE_ID, PS_BOOK_DT.PS_BOOK_ID, PS_BOOK_DT.PS_BOOK_MACHINE_ID, PS_BOOK_DT.PS_BOOK_MOULD_ID, md.StandardMouldCavity, md.StandardCycleTime,
                   PS_BOOK_DT.PS_BOOK_BOM_ID, PS_BOOK_DT.PS_BOOK_ROUTE_ID,
                   PS_BOOK_DT.PS_BOOK_WC, PS_BOOK_DT.PS_BOOK_DATETIME, PS_BOOK_DT.PS_BOOK_DATETIME2, PS_BOOK_DT.PS_BOOK_QTY, PS_BOOK_DT.PS_BOOK_TOTAL_QTY,
                   PS_BOOK_DT.PS_BOOK_STK_FG
            from ierpPM.PS_BOOK_DT, ierpadmin.mould_details md, (select distinct PS_BOOK_ID,  PS_BOOK_SOURCE_ID
                                                                 from ierpPM.PS_BOOK_HDR
                                                                 where PS_BOOK_SOURCE_ID in (select distinct  SO_HDR.SO_ID
                                                                                              from  ierpSM.SO_HDR SO_HDR, ierpSM.SO_DT SO_DT
                                                                                              where SO_HDR.CO_ID = SO_DT.CO_ID
                                                                                              and SO_HDR.SO_ID = SO_DT.SO_ID
                                                                                              and SO_HDR.SO_STATUS not in ('D', 'C')
                                                                                              and SO_DT.SO_SEQ_STATUS not in ('D', 'C')
                                                                                              and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')  )
                                                                ) as PS_HDR
            where PS_BOOK_DT.PS_BOOK_MOULD_ID =  md.mouldNo  COLLATE utf8_general_ci
            and PS_BOOK_DT.PS_BOOk_STATUS <> 'D' and PS_BOOK_DT.deleted_at is null
            and substring(PS_BOOK_DT.PS_BOOK_STK_FG,1,1) not in ('C', 'W')
            and PS_BOOK_DT.PS_BOOK_ID = PS_HDR.PS_BOOK_ID
        ) TblPlanning
        on TblNonComplete.SO_STK_CODE = TblPlanning.PS_BOOK_STK_FG
		and TblNonComplete.SO_ID = TblPlanning.PS_BOOK_SOURCE_ID

        left join
        (
           select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct PRD_BOM_DT.BOM_FG, PRD_BOM_DT.BOM_RAW_STKCODE
            from ierpPM.PRD_BOM_DT
            where PRD_BOM_DT.BOM_RAW_STATUS = 'A'
            and substring(PRD_BOM_DT.BOM_RAW_STKCODE,1,1) = 'W'
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_RAW_STKCODE
          and STK_STD_SETTING.STK_DEFAULT = 'S'

          union all

          select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct BOM_FG, BOM_STD_MACHINE, BOM_STD_MOULD
            from ierpPM.PRD_BOM_HDR
            where BOM_STATUS = 'Y'
            and BOM_STD_MACHINE <> 'MANUAL'
            and substring(BOM_FG,1,1) not in ('W','C')
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_FG
          and STK_STD_SETTING.STK_DEFAULT = 'S'
        ) TblStandardBOM
        on TblStandardBOM.BOM_FG = TblNonComplete.SO_STK_CODE

        where TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD <> 0
    ) TblOutstandingProcessTime
    on TblOutstandingProcessTime.SO_ID = SO_DT.SO_ID
    and TblOutstandingProcessTime.SO_STK_CODE = SO_DT.SO_STK_CODE

    where 1=1

    and SO_HDR.SO_AR = AR_MST_CUSTOMER.AR_ID
    and SO_HDR.CO_ID = SO_DT.CO_ID
    and SO_HDR.SO_ID = SO_DT.SO_ID
    and SO_HDR.SO_ID = TBLDLT.SO_HDR_ID
    and SO_HDR.SO_STATUS not in ('D', 'C')
    and SO_DT.SO_SEQ_STATUS not in ('D')
    and substring(SO_DT.SO_STK_CODE,1,1) not in ('T','Y')
    -- and SO_HDR.SO_ID = 'SO23/00813'

    group by  SO_HDR.SO_ID, SO_HDR.SO_AR,
              AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
              AR_MST_CUSTOMER.AR_CAT1,
              SO_HDR.SO_DATE, SO_HDR.SO_MKT_OWNER,
              TBLDLT.DLT
) TblDashboard
where TotalSOQty <> (Done_pbag_PRD + Done_pbag_STK)

union all

Select MO_ID, AR_NAMES, MKT_OWNER, DLT, DLT_TP, DATEDIFF(DLT_TP, now()) as DiffDay,  completion_STK, completion_PRD, TotalSOQty, TotalSTKQty, TotalPRDQty,
       IF(DLT = null,'-',if (DATEDIFF(DLT_TP, now()) <= 5 , if(TotalPRDQty <> 0, totalhrs , '-') , '-' )) as OUTSTANDING_PROCESS_TIME
from
(
    select  MO_HDR.MO_ID, MO_HDR.MO_AR,
            AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
            AR_MST_CUSTOMER.AR_CAT1,
            (select MTN_DESC from ierpSM.MTN_MST where CLASS_ID = 'AR_CAT1' and MTN_ID = AR_MST_CUSTOMER.AR_CAT1  ) as ARCAT1,
            MO_HDR.MO_DATE,
            AR_MST_CUSTOMER.AR_MKT_OWNER,
            (select MTN_MKT_OWNER.MKT_OWNER_DESC from ierpSM.MTN_MKT_OWNER
             where MTN_MKT_OWNER.MKT_OWNER_ID = AR_MST_CUSTOMER.AR_MKT_OWNER) as MKT_OWNER,
            sum(MO_DT.MO_QTY) as TotalSOQty, 0 as TotalSTKQty, sum(MO_DT.MO_PRD_QTY) as TotalPRDQty,
            ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD,
            0 as Done_pbag_STK,
            TBLDLT.DLT,
            DATE_ADD(TBLDLT.DLT, INTERVAL -2 DAY) as DLT_TP,
            0 as completion_STK,
            ifnull(((ifnull(sum(Tbl_whqrmaster.pbag),0) / sum(MO_DT.MO_PRD_QTY)) * 100),0) as completion_PRD,
            DATEDIFF(TBLDLT.DLT, MO_HDR.MO_DATE) as DIFF_MO_DLT,
            sum(TblOutstandingProcessTime.totalhrs) as totalhrs

    from  ierpSM.AR_MST_CUSTOMER, ierpSM.MO_HDR MO_HDR,
          (select distinct MO_ID,  max(MO_SEQ_DLT) as DLT from ierpSM.MO_DT where deleted_at is null group by MO_ID)  TBLDLT  ,
          ierpSM.MO_DT MO_DT

    left join ( select sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , sum(pbag) as pbag, 0 as looseitem
                from ierpadmin.qrmaster
                where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                and substring(sonum, 1, 2) = 'MO'
                and status = 'wh'
                group by sonum, stockcode )
    Tbl_whqrmaster
    on MO_DT.MO_ID = Tbl_whqrmaster.sonum
    and MO_DT.MO_STK_CODE = Tbl_whqrmaster.stockcode

    left join
    (
        select distinct TblNonComplete.MO_ID, TblNonComplete.MO_STK_CODE as MO_STK_CODE, TblNonComplete.TotalPRDQty, TblNonComplete.Done_pbag_PRD,
               round(((((TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD) /  ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)))
               * ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME)) / 3600),0) as totalhrs,
               ifnull(TblPlanning.PS_BOOK_MACHINE_ID, TblStandardBOM.STK_MACHINE) as machine,
               ifnull(TblPlanning.PS_BOOK_MOULD_ID, TblStandardBOM.STK_MOULD) as mould,
               ifnull(TblPlanning.StandardMouldCavity, ifnull(TblStandardBOM.STK_CAVITY,1)) as Cavity ,
               ifnull(TblPlanning.StandardCycleTime, TblStandardBOM.STK_CYCLETIME) as CycleTime
        from
        (
            select distinct MO_HDR.MO_ID, MO_HDR.MO_AR, MO_DT.MO_STK_CODE,
                    sum(MO_DT.MO_QTY) as TotalSOQty, 0 as TotalSTKQty, sum(MO_DT.MO_PRD_QTY) as TotalPRDQty,
                    ifnull(sum(Tbl_whqrmaster.pbag),0) as Done_pbag_PRD

            from  ierpSM.MO_HDR MO_HDR,
                  ierpSM.MO_DT MO_DT

            left join (select wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, sum(looseitem) as looseitem
                       from (

                            select (select warehouse_id from device where device.deviceId = qrmaster.deviceId) as wh_from, sonum , stockcode, max(convert(dt_whackwrev, date)) as dt_whackwrev , whackwrev_by,  sum(pbag) as pbag, 0 as looseitem
                            from ierpadmin.qrmaster
                            where date_format(dt_whackwrev, '%Y-%m-%d') >=  DATE_SUB(now() ,interval 24 month)
                            and substring(sonum, 1, 2) = 'MO'
                            and status = 'wh'
                            group by qrmaster.deviceId, sonum, stockcode, whackwrev_by


                      ) TblA
                    group by wh_from, sonum, stockcode,  whackwrev_by)
            Tbl_whqrmaster
            on MO_DT.MO_ID = Tbl_whqrmaster.sonum
            and MO_DT.MO_STK_CODE = Tbl_whqrmaster.stockcode

            where 1=1

            and MO_HDR.CO_ID = MO_DT.CO_ID
            and MO_HDR.MO_ID = MO_DT.MO_ID
            and MO_HDR.MO_STATUS not in ('D', 'C')
            and MO_DT.MO_SEQ_STATUS not in ('D', 'C')
            and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y')
            group by  MO_HDR.MO_ID, MO_HDR.MO_AR, MO_DT.MO_STK_CODE

        ) TblNonComplete
        left join
        (
            select distinct PS_HDR.PS_BOOK_SOURCE_ID, PS_BOOK_DT.PS_BOOK_ID, PS_BOOK_DT.PS_BOOK_MACHINE_ID, PS_BOOK_DT.PS_BOOK_MOULD_ID, md.StandardMouldCavity, md.StandardCycleTime,
                   PS_BOOK_DT.PS_BOOK_BOM_ID, PS_BOOK_DT.PS_BOOK_ROUTE_ID,
                   PS_BOOK_DT.PS_BOOK_WC, PS_BOOK_DT.PS_BOOK_DATETIME, PS_BOOK_DT.PS_BOOK_DATETIME2, PS_BOOK_DT.PS_BOOK_QTY, PS_BOOK_DT.PS_BOOK_TOTAL_QTY,
                   PS_BOOK_DT.PS_BOOK_STK_FG
            from ierpPM.PS_BOOK_DT, ierpadmin.mould_details md, (select distinct PS_BOOK_ID,  PS_BOOK_SOURCE_ID
                                           from ierpPM.PS_BOOK_HDR
                                           where PS_BOOK_SOURCE_ID in (select distinct  MO_HDR.MO_ID
                                                                        from  ierpSM.MO_HDR MO_HDR,
                                                                              ierpSM.MO_DT MO_DT
                                                                        where MO_HDR.CO_ID = MO_DT.CO_ID
                                                                        and MO_HDR.MO_ID = MO_DT.MO_ID
                                                                        and MO_HDR.MO_STATUS not in ('D', 'C')
                                                                        and MO_DT.MO_SEQ_STATUS not in ('D', 'C')
                                                                        and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y') )
                                          ) as PS_HDR
            where PS_BOOK_DT.PS_BOOK_MOULD_ID =  md.mouldNo  COLLATE utf8_general_ci
            and PS_BOOK_DT.PS_BOOk_STATUS <> 'D' and PS_BOOK_DT.deleted_at is null
            and PS_BOOK_DT.PS_BOOK_ID = PS_HDR.PS_BOOK_ID
        ) TblPlanning
        on TblNonComplete.MO_STK_CODE = TblPlanning.PS_BOOK_STK_FG
        and TblNonComplete.MO_ID = TblPlanning.PS_BOOK_SOURCE_ID

        left join
        (
           select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct PRD_BOM_DT.BOM_FG, PRD_BOM_DT.BOM_RAW_STKCODE
            from ierpPM.PRD_BOM_DT
            where PRD_BOM_DT.BOM_RAW_STATUS = 'A'
            and substring(PRD_BOM_DT.BOM_RAW_STKCODE,1,1) = 'W'
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_RAW_STKCODE
          and STK_STD_SETTING.STK_DEFAULT = 'S'

          union all

          select distinct TblDT.BOM_FG, STK_STD_SETTING.STK_MACHINE, STK_STD_SETTING.STK_MOULD, STK_CAVITY, STK_CYCLETIME
          from
          (
            select distinct BOM_FG, BOM_STD_MACHINE, BOM_STD_MOULD
            from ierpPM.PRD_BOM_HDR
            where BOM_STATUS = 'Y'
            and BOM_STD_MACHINE <> 'MANUAL'
            and substring(BOM_FG,1,1) not in ('C')
          ) TblDT
          left join ierpadmin.STK_STD_SETTING on STK_STD_SETTING.STK_STKCODE = TblDT.BOM_FG
          and STK_STD_SETTING.STK_DEFAULT = 'S'
        ) TblStandardBOM
        on TblStandardBOM.BOM_FG = TblNonComplete.MO_STK_CODE

        where TblNonComplete.TotalPRDQty - TblNonComplete.Done_pbag_PRD <> 0
    ) TblOutstandingProcessTime
    on TblOutstandingProcessTime.MO_ID = MO_DT.MO_ID
    and TblOutstandingProcessTime.MO_STK_CODE = MO_DT.MO_STK_CODE

    where 1=1

    and MO_HDR.MO_AR = AR_MST_CUSTOMER.AR_ID
    and MO_HDR.CO_ID = MO_DT.CO_ID
    and MO_HDR.MO_ID = MO_DT.MO_ID
    and MO_HDR.MO_ID = TBLDLT.MO_ID
    and MO_HDR.MO_STATUS not in ('D', 'C')
    and MO_DT.MO_SEQ_STATUS not in ('D')
    and substring(MO_DT.MO_STK_CODE,1,1) not in ('T','Y')
    group by  MO_HDR.MO_ID, MO_HDR.MO_AR,
              AR_MST_CUSTOMER.AR_NAME1, AR_MST_CUSTOMER.AR_NAMES,
              AR_MST_CUSTOMER.AR_CAT1,
              MO_HDR.MO_DATE, AR_MST_CUSTOMER.AR_MKT_OWNER,
              TBLDLT.DLT
) TblDashboard
where TotalSOQty <> (Done_pbag_PRD + Done_pbag_STK)
    ");
        }



        return view('welcome')->with('results', $results);
    }


    public function productDashHourly()
    {
        $currentHour = Carbon::now()->format('H:00:00');

        return view('Dashboard.ProductionHourly', compact('currentHour'));
    }

    public function productDashHourlyAjax()
    {
        $currentHour = Carbon::now()->format('H:00:00');

        $theData = DB::connection('mysql')
            ->select("
            select t_time as 'TIME', target as 'HOURLY TARGET (TARGET)',
                target_accum as 'DAILY TARGET (PACK)', actual as 'HOURLY TOTAL (ACTUAL)', prd_accum as 'TODAY''S TOTAL (PACK)', prd_accum - target_accum as 'ACHIEVEMENT (PACK)'
            from
            (
                select t_time, target, round(@running_target:=@running_target + target ,0) as target_accum, cnt_prd as actual, round(@running_total:=@running_total + cnt_prd ,0) as prd_accum
                from
                (
                    select target, numbers.num as t_time, count(c.dt_opscancomplete) cnt_prd
                    from
                    (
                        select T_HOUR as num, T_HRS_TARGET as target
                        from ierpadmin.TARGET_PRD_HOURLY
                        where T_DAYNAME = DAYNAME(CURDATE())

                    ) numbers
                    left join
                    (
                        select  qrmaster.dt_opscancomplete, count(qrcode) from ierpadmin.qrmaster where convert( qrmaster.dt_opscancomplete,date) between CURDATE() and CURDATE()
                        group by  qrmaster.dt_opscancomplete
                    ) c on hour(c.dt_opscancomplete) = hour(numbers.num)
                    group by numbers.num, numbers.target
                ) TempA
                JOIN (SELECT @running_total:=0) r
                JOIN (SELECT @running_target:=0) q
            ) TempZ;
        ");

        return response()->json(['data' => $theData, 'currentHour' => $currentHour]);
    }

    public function productDashDailyReport()
    {
        $currentHour = Carbon::now()->format('H:00:00');

        return view('Dashboard.ProductionDaily', compact('currentHour'));
    }

    public function productDashDailyReportAjax(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $currentHour = Carbon::now()->format('H:00:00');

        $theData = DB::connection('mysql')
            ->select("
            select t_time as 'TIME', target as 'HOURLY TARGET (TARGET)',
                target_accum as 'DAILY TARGET (PACK)', actual as 'HOURLY TOTAL (ACTUAL)', prd_accum as 'TODAY''S TOTAL (PACK)', prd_accum - target_accum as 'ACHIEVEMENT (PACK)'
            from
            (
                select t_time, target, round(@running_target:=@running_target + target ,0) as target_accum, cnt_prd as actual, round(@running_total:=@running_total + cnt_prd ,0) as prd_accum
                from
                (
                    select target, numbers.num as t_time, count(c.dt_opscancomplete) cnt_prd
                    from
                    (
                        select T_HOUR as num, T_HRS_TARGET as target
                        from ierpadmin.TARGET_PRD_HOURLY
                        where T_DAYNAME = DAYNAME(STR_TO_DATE('" . $endDate . "', '%Y-%m-%d'))

                    ) numbers
                    left join
                    (
                    select  qrmaster.dt_opscancomplete, count(qrcode) from ierpadmin.qrmaster where convert( qrmaster.dt_opscancomplete,date) between '" . $startDate . "' and '" . $endDate . "'
                    group by  qrmaster.dt_opscancomplete
                    ) c on hour(c.dt_opscancomplete) = hour(numbers.num)
                    group by numbers.num, numbers.target
                ) TempA
                JOIN (SELECT @running_total:=0) r
                JOIN (SELECT @running_target:=0) q
            ) TempZ;
        ");

        return response()->json(['data' => $theData, 'currentHour' => $currentHour]);
    }

    public function dailyEfficientTrack()
    {
        $currentHour = Carbon::now()->format('H:00:00');

        return view('Dashboard.DailyEfficientTrackerDashboard', compact('currentHour'));
    }

    public function dailyEfficientTrackAjax(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $currentHour = Carbon::now()->format('H:00:00');

        $theData = DB::connection('mysql')
            ->select("
            select day_Name as 'DAY', dayNo as 'DATE', noofworker as 'TOTAL WORKER', actual_work_hour as 'TOTAL WORKING HOUR', OT_hour as 'TOTAL OT HOUR',
                Target as 'DAILY TARGET', cnt_prd as 'PRODUCTION - OPERATOR SCAN (PACK)', totalopscan as 'CUMULATIVE PRODUCTION TOTAL (PACK)',
                IF (totalmaxhour = 0, 0, round(totalopscan / totalmaxhour, 0)) as 'ARL', ratio as 'WORKING DAY MANPOWER RATIO',
                cnt_wh as 'WAREHOUSE - SCAN RECEIVED (PACK)', totalwhscan as 'CUMULATIVE WAREHOUSE RECEIVED (PACK)',
                IF (totalwhmaxhour = 0, 0, round(totalwhscan / totalwhmaxhour, 0)) as 'WH ARL',
                cnt_do as 'DO (PACK)', totaldo as 'CUMULATIVE PACK DO (PACK)',
                IF (totaldomaxhour = 0, 0, round(totaldo / totaldomaxhour, 0)) as 'DO ARL'
            from
            (
                select day_Name, dayNo, noofworker, actual_work_hour, OT_hour, Target, cnt_prd, max_hour, round(@arl_hrs:=@arl_hrs + max_hour ,2) as totalmaxhour,
                round(@arl_cnt:=@arl_cnt + cnt_prd ,0) as totalopscan,
                        IF (working_hour = 0, 0, round( cnt_prd / working_hour, 1)) as ratio,
                        cnt_wh, whmax_hour, round(@arl_whhrs:=@arl_whhrs + whmax_hour ,2) as totalwhmaxhour,  round(@arl_whcnt:=@arl_whcnt + cnt_wh ,0) as totalwhscan,
                        cnt_do, domax_hour, round(@arl_dohrs:=@arl_dohrs + domax_hour ,2) as totaldomaxhour,  round(@arl_docnt:=@arl_docnt + cnt_do ,0) as totaldo
                from
                (
                    select daily.day_Name, daily.dayNo, ifnull(attendance.noofworker,0) as noofworker, ifnull(attendance.working_hour,0) as working_hour,
                            ifnull(attendance.actual_work_hour,0) as actual_work_hour,
                            ifnull(attendance.OT_hour,0) as OT_hour,  300 as Target,
                            ifnull(operatorscan.cnt_prd,0) as cnt_prd,  ifnull(qrmtscan.max_hour,0) / (24 * 3600) as max_hour,
                            ifnull(whRevscan.cnt_wh,0) as cnt_wh, ifnull(hr_whscan.whmax_hour,0) / (24 * 3600) as whmax_hour,
                            ifnull(dopack.cnt_do,0) as cnt_do, ifnull(hr_dopack.domax_hour,0) / (24 * 3600) as domax_hour
                    from
                    (
                        SELECT DAYNAME('" . $startDate . "' + INTERVAL t.n - 1 DAY) day_Name , '" . $startDate . "' + INTERVAL t.n - 1 DAY dayNo
                        FROM ( SELECT a.N as A, b.N as B, c.N as C,  a.N + b.N * 10 + c.N * 100 + 1 n
                                FROM
                                (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                                ,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                                ,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
                                ORDER BY n ) t
                        WHERE t.n <= DATEDIFF('" . $endDate . "', '" . $startDate . "') + 1
                    ) daily

                    left join
                    (
                        select convert(daily_date,date) as daily_date, count(*) noofworker,
                                (sum((case workday when 1 then 9 when 0.50 then 5 else 0 end)) + sum(OT1hr) + sum(OT2hr) + sum(OT3hr) ) as working_hour,
                                sum((case workday when 1 then 9 when 0.50 then 5 else 0 end)) as actual_work_hour,
                                (sum(OT1hr) + sum(OT2hr) + sum(OT3hr)) as OT_hour
                        from ierpSM.manpower
                        where convert(daily_date,date) between '" . $startDate . "' and '" . $endDate . "'
                        and section like 'D-%'
                        and section <> 'D-ZINV'
                        and workday > 0
                        and status = 'A'
                        group by convert(daily_date,date)
                    ) attendance on daily.dayNo = attendance.daily_date

                    left join
                    (
                        select  convert( qrmaster.dt_opscancomplete,date) as dt_opscancomplete, count(qrcode) as cnt_prd
                        from ierpadmin.qrmaster
                        where convert( qrmaster.dt_opscancomplete,date) between '" . $startDate . "' and '" . $endDate . "'
                        group by  convert( qrmaster.dt_opscancomplete,date)
                    ) operatorscan on operatorscan.dt_opscancomplete = daily.dayNo

                    left join
                    (
                        select convert( qrmaster.dt_opscancomplete,date) as qrmtscan, time_to_sec(max(qrmaster.dt_opscancomplete)) as max_hour
                        from ierpadmin.qrmaster
                        where convert( qrmaster.dt_opscancomplete,date) between '" . $startDate . "' and '" . $endDate . "'
                        and convert( qrmaster.dt_opscancomplete,time) < '08:00:00'
                        and DAYNAME(convert( qrmaster.dt_opscancomplete,date)) in ('Saturday', 'Sunday')
                        group by  convert( qrmaster.dt_opscancomplete,date)

                        union all

                        select convert( qrmaster.dt_opscancomplete,date) as qrmtscan, (24 * 3600) as max_hour
                        from ierpadmin.qrmaster
                        where convert( qrmaster.dt_opscancomplete,date) between '" . $startDate . "' and '" . $endDate . "'
                        and DAYNAME(convert( qrmaster.dt_opscancomplete,date)) not in ('Saturday', 'Sunday')
                        group by  convert( qrmaster.dt_opscancomplete,date)
                    ) qrmtscan on qrmtscan.qrmtscan = daily.dayNo

                    left join
                    (
                        select convert(dt_whackwrev,date) as dt_whackwrev, count(qrcode) as cnt_wh
                        from ierpadmin.qrmaster
                        where convert(dt_whackwrev,date) between '" . $startDate . "' and '" . $endDate . "'
                        group by convert(dt_whackwrev,date)
                    ) whRevscan on whRevscan.dt_whackwrev = daily.dayNo

                    left join
                    (
                        select convert( qrmaster.dt_whackwrev,date) as whqrmtscan, (24 * 3600) as whmax_hour
                        from ierpadmin.qrmaster
                        where convert( qrmaster.dt_whackwrev,date) between '" . $startDate . "' and '" . $endDate . "'
                        and DAYNAME(convert( qrmaster.dt_whackwrev,date)) not in ('Saturday', 'Sunday')
                        group by  convert( qrmaster.dt_whackwrev,date)
                    ) hr_whscan on hr_whscan.whqrmtscan = daily.dayNo

                    left join
                    (
                        select convert(DO_DATE,date) as do_date, sum(DO_TOTAL_PACK)  as cnt_do
                        from ierpSM.DO_HDR
                        where convert(DO_DATE,date) between '" . $startDate . "' and '" . $endDate . "'
                        group by convert(DO_DATE,date)
                    ) dopack on dopack.do_date = daily.dayNo

                    left join
                    (
                        select convert(DO_DATE,date) as do_date, (24 * 3600) as domax_hour
                        from ierpSM.DO_HDR
                        where convert(DO_DATE,date) between '" . $startDate . "' and '" . $endDate . "'
                        and DAYNAME(convert(DO_DATE,date)) not in ('Saturday', 'Sunday')
                        group by  convert(DO_DATE,date)
                    ) hr_dopack on hr_dopack.do_date = daily.dayNo
                ) TempA
                JOIN (SELECT @arl_cnt:=0) r
                JOIN (SELECT @arl_hrs:=0) q
                JOIN (SELECT @arl_whcnt:=0) s
                JOIN (SELECT @arl_whhrs:=0) t
                JOIN (SELECT @arl_docnt:=0) x
                JOIN (SELECT @arl_dohrs:=0) y
            ) TableA;
        ");

        return response()->json(['data' => $theData, 'currentHour' => $currentHour]);
    }

    public function hourlyEfficientTrackPE()
    {
        $currentHour = Carbon::now()->format('H:00:00');
        $date = date('Y-m-d');

        DB::statement("delete from ierpadmin.TEMP_PE_DAILY_REPORT
                    where convert( TrxDate, date ) between '$date' AND '$date'
                    and TrxUsr = '" . auth()->user()->id . "'");

        DB::statement("call ierpadmin.SP_GENERATE_DAILY_TARGET_PE('$date', '$date', '" . auth()->user()->id . "')");

        return view('Dashboard.HourlyEfficientTrackerPEDashboard', compact('currentHour'));
    }

    public function hourlyEfficientTrackPEAjax(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $date = date('Y-m-d');

        $currentHour = Carbon::now()->format('H:00:00');

        // Using startDate as the date
        DB::statement("delete from ierpadmin.TEMP_PE_DAILY_REPORT
                    where convert( TrxDate, date ) between '$startDate' AND '$startDate'
                    and TrxUsr = '" . auth()->user()->id . "'");

        // Using startDate as the date
        DB::statement("call ierpadmin.SP_GENERATE_DAILY_TARGET_PE('$startDate', '$startDate', '" . auth()->user()->id . "')");

        $theData = DB::connection('mysql')
            ->select("
                select t_time as 'TIME', target as 'HOURLY TARGET (TARGET)',
                    round(target_accum,3) as 'DAILY TARGET (KG)', actual as 'HOURLY TOTAL (ACTUAL)', round(prd_accum,3) as 'TODAY''S TOTAL (KG)', round(prd_accum - target_accum,3) as 'ACHIEVEMENT (KG)',
                    waste as 'WASTE (KG)', round(waste_accum,3) as 'TODAY''S WASTE (KG)'
                from
                (
                    select t_time, round(target,3) as target, round(@running_target:=@running_target + target ,3) as target_accum, round(cnt_prd,3) as actual, round(@running_total:=@running_total + cnt_prd ,3) as prd_accum,
                        round(cnt_waste,3) as waste, round(@running_waste:=@running_waste + cnt_waste ,3) as waste_accum
                    from
                    (
                        select target, numbers.num as t_time, ifnull(round(sum(c.SFUKG),3),0) cnt_prd, ifnull(round(sum(c.WASTEKG),3),0) cnt_waste
                        from
                        (
                            select T_HOUR as num, PE_HRS_TARGET as target
                            from ierpadmin.TARGET_PRD_HOURLY
                            where T_DAYNAME = DAYNAME(STR_TO_DATE('$date', '%Y-%m-%d'))
                        ) numbers
                        left join
                        (
                            select SfuDate, ifnull(round(sum(Tbl_b.TotalActualQtyOut),3),0) as SFUKG, ifnull(round(sum(Tbl_b.actualwaste),3),0) as WASTEKG
                            from
                            (
                                select ' ' as rowtype, Date_format(TEMP_PE_DAILY_REPORT.TrxDate, '%Y-%m') as TrxMthYr, TEMP_PE_DAILY_REPORT.TrxStkID as TrxStkID,
                                    TEMP_PE_DAILY_REPORT.TrxStkCode as stkcode,
                                    STK_MST.STK_SHORT_NAME as stknameS, STK_MST.STK_CAT1 as StkCat1,
                                    TEMP_PE_DAILY_REPORT.TrxWH as TrxWH,
                                    TEMP_PE_DAILY_REPORT.TrxQtyDB as TotalActualQtyIn,
                                    TEMP_PE_DAILY_REPORT.TrxQtyCR as TotalActualQtyOut,
                                    TEMP_PE_DAILY_REPORT.TrxUOM as TrxStkUOM, TEMP_PE_DAILY_REPORT.Trxdate as SfuDate, TEMP_PE_DAILY_REPORT.TrxWH as SfuFgWh,
                                    STK_MST.STK_CAT1 as RawStkCat1, TEMP_PE_DAILY_REPORT.TrxStkCode as RawStockCode, STK_MST.STK_SHORT_NAME as Stock,
                                    TEMP_PE_DAILY_REPORT.TrxUOM as BomRawQtyPerUom,
                                    0 as TotalStandardWaste,
                                    (TEMP_PE_DAILY_REPORT.TrxQtyCR ) as TotalScrapWaste,
                                    0 as actualwaste
                                    from ierpadmin.TEMP_PE_DAILY_REPORT TEMP_PE_DAILY_REPORT, ierpSM.STK_MST STK_MST
                                    where TEMP_PE_DAILY_REPORT.TrxStkCode = STK_MST.STK_CODE
                                    and TEMP_PE_DAILY_REPORT.TrxStatus = 'A'
                                    and TEMP_PE_DAILY_REPORT.TrxStkCode = 'R0PE10H1000'
                                    and TEMP_PE_DAILY_REPORT.TrxUsr = '" . auth()->user()->id . "'
                                    and convert(TEMP_PE_DAILY_REPORT.Trxdate,date) between '" . $startDate . "' and '" . $endDate . "'

                                union all

                                select ' ' as rowtype, Date_format(STK_LEDGER_ALL.TrxDate, '%Y-%m') as TrxMthYr, STK_LEDGER_ALL.TrxStkID as TrxStkID,
                                    STK_LEDGER_ALL.TrxStkCode as stkcode,
                                    STK_MST.STK_SHORT_NAME as stknameS, STK_MST.STK_CAT1 as StkCat1,
                                    STK_LEDGER_ALL.TrxWH as TrxWH,
                                    0 as TotalActualQtyIn,
                                    0 as TotalActualQtyOut,
                                    STK_LEDGER_ALL.TrxUOM as TrxStkUOM, STK_LEDGER_ALL.Trxdate as SfuDate, STK_LEDGER_ALL.TrxWH as SfuFgWh,
                                    STK_MST.STK_CAT1 as RawStkCat1, STK_LEDGER_ALL.TrxStkCode as RawStockCode, STK_MST.STK_SHORT_NAME as Stock,
                                    STK_LEDGER_ALL.TrxUOM as BomRawQtyPerUom,
                                    0 as TotalStandardWaste,
                                    (STK_LEDGER_ALL.TrxQtyCR ) as TotalScrapWaste,
                                    ((STK_LEDGER_ALL.TrxQtyCR ) + 0 ) as actualwaste
                                    from ierpSM.STK_LEDGER_ALL STK_LEDGER_ALL, ierpSM.STK_MST STK_MST
                                    where STK_LEDGER_ALL.TrxStkCode = STK_MST.STK_CODE
                                    and STK_LEDGER_ALL.TrxType in ('QLC-BUYOFF','QLC-LAB','QLC-SCRAP','SFU-LAB' ,'SFU-BUYOFF', 'SFU-SCRAP')
                                    and substring(STK_MST.STK_CAT1,1,1) in ('R', 'S')
                                    and STK_LEDGER_ALL.TrxWH in ('1WHP', '1WHO', '1WHH')
                                    and STK_LEDGER_ALL.TrxStatus = 'A'
                                    and STK_LEDGER_ALL.TrxStkCode = 'R0PE10H1000'
                                    and convert(STK_LEDGER_ALL.Trxdate,date) between '" . $startDate . "' and '" . $endDate . "'
                            ) Tbl_b
                            group by SfuDate
                        ) c on hour(c.SfuDate) = hour(numbers.num)
                        group by numbers.num, numbers.target
                    ) TempA
                    JOIN (SELECT @running_total:=0) r
                    JOIN (SELECT @running_target:=0) q
                    JOIN (SELECT @running_waste:=0) p
                ) TempZ;
            ");

        return response()->json(['data' => $theData, 'currentHour' => $currentHour]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
