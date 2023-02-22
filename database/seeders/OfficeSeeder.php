<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Office::factory()->create([
            'office_name' => 'Office of the College President',
            'office_abbr' => 'CP',
        ]); // 1
        
    //    _________________________________________________________________					

        Office::factory()->create([
            'office_name' => 'Office of the Director for Executive Affairs',
            'office_abbr' => 'DEA',
            'parent_id' => 1
        ]); // 2
        
        Office::factory()->create([
            'office_name' => 'Records Management Office',
            'office_abbr' => 'RMO',	
            'parent_id' => 2
        ]); // 3
        
        Office::factory()->create([
            'office_name' => 'Gender & Development Office',
            'office_abbr' => 'GDO',	
            'parent_id' => 2
        ]); // 4
        
        Office::factory()->create([
            'office_name' => 'Alumni Affairs Office',
            'office_abbr' => 'AAO',	
            'parent_id' => 2
        ]); // 5
        
        Office::factory()->create([
            'office_name' => 'Motor Pool Services Office',
            'office_abbr' => 'MPSO',	
            'parent_id' => 2
        ]); // 6
        
    //    _____________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Head for College Secretarial Affairs',
            'office_abbr' => 'HCSA',	
            'parent_id' => 1
        ]); // 7
        
        Office::factory()->create([
            'office_name' => 'Office of the Legal Retainer',
            'office_abbr' => 'OLR',	
            'parent_id' => 1
        ]); // 8
        
        Office::factory()->create([
            'office_name' => 'Office of the Auditor for Internal Control Services',
            'office_abbr' => 'AICS',	
            'parent_id' => 1
        ]); // 9
        
        Office::factory()->create([
            'office_name' => 'Office of the Public Information Officer',
            'office_abbr' => 'PIO',	
            'parent_id' => 1
        ]); // 10

    //    ______________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Vice President for Academic Affairs',
            'office_abbr' => 'VPAA',	
            'parent_id' => 1
        ]); // 11
        
        Office::factory()->create([
            'office_name' => 'Office of the IAAS Dean',
            'office_abbr' => 'OIAAS',	
            'parent_id' => 11
        ]); // 12
        
        Office::factory()->create([
            'office_name' => 'Office of the IC Dean',
            'office_abbr' => 'OIC',	
            'parent_id' => 11
        ]); // 13
        
        Office::factory()->create([
            'office_name' => 'Office of the IHSS Dean',
            'office_abbr' => 'OIHSS',	
            'parent_id' => 11
        ]); // 14
        
        Office::factory()->create([
            'office_name' => 'Office of the ILEGG Dean',
            'office_abbr' => 'OILEGG',	
            'parent_id' => 11
        ]); // 15
        
        Office::factory()->create([
            'office_name' => 'Office of the ITEd Dean',
            'office_abbr' => 'OITEd',	
            'parent_id' => 11
        ]); // 16
        
        Office::factory()->create([
            'office_name' => 'Office of the IAdS Dean',
            'office_abbr' => 'OIAdS',	
            'parent_id' => 11
        ]); // 17
        
        Office::factory()->create([
            'office_name' => 'Office of the Student Dev & Services Director',
            'office_abbr' => 'OSDSD',	
            'parent_id' => 11
        ]); // 18
        
        Office::factory()->create([
            'office_name' => 'Office of the College Librarian',
            'office_abbr' => 'OCL',	
            'parent_id' => 11
        ]); // 19
        
        Office::factory()->create([
            'office_name' => 'Office of the College Registrar',
            'office_abbr' => 'OCR',	
            'parent_id' => 11
        ]); // 20
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for Student Admission',
            'office_abbr' => 'OHSA',	
            'parent_id' => 11
        ]); // 21
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for NSTP',
            'office_abbr' => 'OHN',	
            'parent_id' => 11
        ]); // 22
        
        Office::factory()->create([
            'office_name' => 'Center for Adult Education and Lifelong Learning',
            'office_abbr' => 'CAELL',	
            'parent_id' => 11
        ]); // 23
        
        Office::factory()->create([
            'office_name' => 'Center for Learning Resources & Materials Development',
            'office_abbr' => 'CLRMD',	
            'parent_id' => 11
        ]); // 24
        
        Office::factory()->create([
            'office_name' => 'College Review Center',
            'office_abbr' => 'CRC',	
            'parent_id' => 11
        ]); // 25

    //    ___________________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Vice-President for Administration & Finance',
            'office_abbr' => 'OVPAF',	
            'parent_id' => 1
        ]); // 26
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for Administrative Services Division',
            'office_abbr' => 'ODASD',	
            'parent_id' => 26
        ]); // 27
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for Finance Services Division',
            'office_abbr' => 'ODFSD',	
            'parent_id' => 26
        ]); // 28
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for HRM Division',
            'office_abbr' => 'ODHD',	
            'parent_id' => 26
        ]); // 29
        
    //    __________________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Vice-President for Research,Ext, & Production',
            'office_abbr' => 'OVPREP',	
            'parent_id' => 1
        ]); // 30
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for Research & Development Division',
            'office_abbr' => 'ODRDD',	
            'parent_id' => 30
        ]); // 31
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for Extension Division',
            'office_abbr' => 'ODED',	
            'parent_id' => 30
        ]); // 32
        
        Office::factory()->create([
            'office_name' => 'Office of the Director for Production & Innovation Division',
            'office_abbr' => 'ODPID',	
            'parent_id' => 30
        ]); // 33
        
    //    ____________________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Director for Planning & Resource Management',
            'office_abbr' => 'ODPRM',	
            'parent_id' => 1
        ]); // 34
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for Planning Management Division',
            'office_abbr' => 'OHPMD',	
            'parent_id' => 34
        ]); // 35
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for Resource Management Division',
            'office_abbr' => 'OHRMD',	
            'parent_id' => 34
        ]); // 36
        
   //     _________________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Office of the Director for Quality Assurance',
            'office_abbr' => 'ODQA',	
            'parent_id' => 1
        ]); // 37
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for Accredatation, Program Compliance, and ISA Division',
            'office_abbr' => 'OHAPCID',	
            'parent_id' => 37
        ]); // 38
        
        Office::factory()->create([
            'office_name' => 'Office of the Head for ISO-QMS Division',
            'office_abbr' => 'OHID',	
            'parent_id' => 37
        ]); // 39

    //    ______________________________________________________________________________________
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the Department Chairpersons of IAAS',
        //     'office_abbr' => 'ODCIAAS',	
        //     'parent_id' => 12
        // ]); // 40
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the Department Chairpersons of IC',
        //     'office_abbr' => 'ODCIC',	
        //     'parent_id' => 13
        // ]); // 41
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the Department Chairpersons of IHSS',
        //     'office_abbr' => 'ODCIHSS',	
        //     'parent_id' => 14
        // ]); // 42
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the Department Chairpersons of ILEGG',
        //     'office_abbr' => 'ODCILEGG',	
        //     'parent_id' => 15
        // ]); // 43
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the Department Chairpersons of ITEd',
        //     'office_abbr' => 'ODCITEd',	
        //     'parent_id' => 16
        // ]); // 44
        
        // Office::factory()->create([
        //     'office_name' => 'Office of the AdS Program Chairs',
        //     'office_abbr' => 'ODCIAdS',	
        //     'parent_id' => 17
        // ]); // 45
        
        Office::factory()->create([
            'office_name' => 'Office of the SDS Coordinators/Heads',
            'office_abbr' => 'ODCDSD',	
            'parent_id' => 18
        ]); // 40
        
    //    ______________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Fisheries & Aquatic Sciences',
            'office_abbr' => 'FAS',	
            'parent_id' => 12
        ]); // 41
        
        Office::factory()->create([
            'office_name' => 'Marine Biology',
            'office_abbr' => 'MB',	
            'parent_id' => 12
        ]); // 42
        
        Office::factory()->create([
            'office_name' => 'Food Technology',
            'office_abbr' => 'FT',	
            'parent_id' => 12
        ]); // 43
        
        Office::factory()->create([
            'office_name' => 'Agro-Forestry',
            'office_abbr' => 'AF',	
            'parent_id' => 12
        ]); // 44
        
        Office::factory()->create([
            'office_name' => 'Institute REP Head of IAAS',
            'office_abbr' => 'IRHIAAS',	
            'parent_id' => 12
        ]); // 45
        
    //    _____________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Information Technology',
            'office_abbr' => 'IT',	
            'parent_id' => 13
        ]); // 46
        
        Office::factory()->create([
            'office_name' => 'Information System',
            'office_abbr' => 'IS',	
            'parent_id' => 13
        ]); // 47
        
        Office::factory()->create([
            'office_name' => 'Institute REP Head if IC',
            'office_abbr' => 'IRHIC',	
            'parent_id' => 13
        ]); // 48
    //    __________________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Communication',
            'office_abbr' => 'CM',	
            'parent_id' => 14
        ]); // 49
        
        
        Office::factory()->create([
            'office_name' => 'General Education',
            'office_abbr' => 'GenEd',	
            'parent_id' => 14
        ]); // 50
        
        Office::factory()->create([
            'office_name' => 'Institute REP Head of IHSS',
            'office_abbr' => 'IRHIHSS',	
            'parent_id' => 14
        ]); // 51
    //    _______________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Public Administration',
            'office_abbr' => 'PubAd',	
            'parent_id' => 15
        ]); // 52
        
        Office::factory()->create([
            'office_name' => 'Disaster Resillency & Mgt.',
            'office_abbr' => 'DRM',	
            'parent_id' => 15
        ]); // 53
        
        Office::factory()->create([
            'office_name' => 'Entreprenuership',
            'office_abbr' => 'Entrep',	
            'parent_id' => 15
        ]); // 54
        
        Office::factory()->create([
            'office_name' => 'Social Work',
            'office_abbr' => 'SW',	
            'parent_id' => 15
        ]); // 55
        
        Office::factory()->create([
            'office_name' => 'Tourism Management',
            'office_abbr' => 'TM',	
            'parent_id' => 15
        ]); // 56
        
        Office::factory()->create([
            'office_name' => 'Institute REP Head of ILEGG',
            'office_abbr' => 'IRHILEGG',	
            'parent_id' => 15
        ]); // 57
    //    ___________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Secondary Education',
            'office_abbr' => 'SecEd',	
            'parent_id' => 16
        ]); // 58
        
        Office::factory()->create([
            'office_name' => 'TLEd',
            'office_abbr' => 'TLEd',	
            'parent_id' => 16
        ]); // 59
        
        Office::factory()->create([
            'office_name' => 'Professional Education',
            'office_abbr' => 'ProfEd',	
            'parent_id' => 16
        ]); // 60
        
        Office::factory()->create([
            'office_name' => 'Elementary Education',
            'office_abbr' => 'ElemEd',	
            'parent_id' => 16
        ]); // 61
        
        Office::factory()->create([
            'office_name' => 'Institute REP Head of ITEd',
            'office_abbr' => 'IRHITEd',	
            'parent_id' => 16
        ]); // 62
    //    ___________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Educational Management',
            'office_abbr' => 'EdMan',	
            'parent_id' => 17
        ]); // 63
        
        Office::factory()->create([
            'office_name' => 'MST & MABE',
            'office_abbr' => 'MM',	
            'parent_id' => 17
        ]); // 64
        
        Office::factory()->create([
            'office_name' => 'Fisheries Mgt. & Marine Biodiversity',
            'office_abbr' => 'FMMB',	
            'parent_id' => 17
        ]); // 65
        
        Office::factory()->create([
            'office_name' => 'Public Administration',
            'office_abbr' => 'PudAd',	
            'parent_id' => 17
        ]); // 66
        
    //    _________________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Scholarship Grants',
            'office_abbr' => 'SG',	
            'parent_id' => 40
        ]); // 67
        
        Office::factory()->create([
            'office_name' => 'Sports',
            'office_abbr' => 'Spts',	
            'parent_id' => 40
        ]); // 68
        
        Office::factory()->create([
            'office_name' => 'Socio-Cultural',
            'office_abbr' => 'SocCul',	
            'parent_id' => 40
        ]); // 69
        
        Office::factory()->create([
            'office_name' => 'Student Organizations',
            'office_abbr' => 'StudOrg',	
            'parent_id' => 40
        ]); // 70
        
        Office::factory()->create([
            'office_name' => 'Student Publication',
            'office_abbr' => 'StudPub',	
            'parent_id' => 40
        ]); // 71
        
        Office::factory()->create([
            'office_name' => 'Student Discipline',
            'office_abbr' => 'StudDis',	
            'parent_id' => 40
        ]); // 72
        
        Office::factory()->create([
            'office_name' => 'Guidance, Counselling & Testing',
            'office_abbr' => 'GCT',	
            'parent_id' => 40
        ]); // 73
        
        Office::factory()->create([
            'office_name' => 'Medical & Dental Services',
            'office_abbr' => 'MDS',	
            'parent_id' => 40
        ]); // 74

    //    _____________________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'English',
            'office_abbr' => 'Eng',	
            'parent_id' => 58
        ]); // 75
        
        Office::factory()->create([
            'office_name' => 'Mathematics',
            'office_abbr' => 'Math',	
            'parent_id' => 58
        ]); // 76
        
        Office::factory()->create([
            'office_name' => 'Sciences',
            'office_abbr' => 'Sci',	
            'parent_id' => 58
        ]); // 77

    //    _______________________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Procurement Services Unit',
            'office_abbr' => 'PSU',	
            'parent_id' => 27
        ]); // 78
        
        
        Office::factory()->create([
            'office_name' => 'Supply & Property Management Unit',
            'office_abbr' => 'SPMU',	
            'parent_id' => 27
        ]); // 79
        
        
        Office::factory()->create([
            'office_name' => 'Physical Facilities & Maintenance Unit',
            'office_abbr' => 'PFMU',	
            'parent_id' => 27
        ]); // 80
        
        
        Office::factory()->create([
            'office_name' => 'Security Services Unit',
            'office_abbr' => 'SSU',	
            'parent_id' => 27
        ]); // 81
        
        
        Office::factory()->create([
            'office_name' => 'Technology Support Services Unit',
            'office_abbr' => 'TSSU',	
            'parent_id' => 27
        ]); // 82
        
        
        Office::factory()->create([
            'office_name' => 'Corporate Enterprise Management Unit',
            'office_abbr' => 'CEMU',	
            'parent_id' => 27
        ]); // 83
    //    __________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Accounting Services Unit',
            'office_abbr' => 'ASU',	
            'parent_id' => 28
        ]); // 84
        
        Office::factory()->create([
            'office_name' => 'Budgeting Services Unit',
            'office_abbr' => 'BSU',	
            'parent_id' => 28
        ]); // 85
        
        Office::factory()->create([
            'office_name' => 'Cashering Services Unit',
            'office_abbr' => 'CSU',	
            'parent_id' => 28
        ]); // 86

    //    ____________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Personnel & Benefits Unit',
            'office_abbr' => 'PBU',	
            'parent_id' => 29
        ]); // 87
        
        
        Office::factory()->create([
            'office_name' => 'Training & Development Unit',
            'office_abbr' => 'TDU',	
            'parent_id' => 29
        ]); // 88
        
        
        Office::factory()->create([
            'office_name' => 'Scholarship Unit',
            'office_abbr' => 'SU',	
            'parent_id' => 29
        ]); // 89

    //    ___________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Applied Communication and Publication Unit',
            'office_abbr' => 'ACPU',	
            'parent_id' => 31
        ]); // 90
        
        Office::factory()->create([
            'office_name' => 'Research Ethics Unit',
            'office_abbr' => 'REU',	
            'parent_id' => 31
        ]); // 91

    //    __________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Community Affairs Unit',
            'office_abbr' => 'CAU',	
            'parent_id' => 32
        ]); // 92
        
        Office::factory()->create([
            'office_name' => 'Intelectual Property & Knowledge and Technology Transfer Unit',
            'office_abbr' => 'IPKTTU',	
            'parent_id' => 32
        ]); // 93

    //    _______________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Production Unit',
            'office_abbr' => 'ProdUn',	
            'parent_id' => 33
        ]); // 94

    //    ___________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Project Monitoring & Evaluation Unit',
            'office_abbr' => 'PMEU',	
            'parent_id' => 35
        ]); // 95
        
        
        Office::factory()->create([
            'office_name' => 'Engineering & Infrastracture Unit',
            'office_abbr' => 'EIU',	
            'parent_id' => 35
        ]); // 96
        
        Office::factory()->create([
            'office_name' => 'PBB & SUC Levelling Unit',
            'office_abbr' => 'PSLU',	
            'parent_id' => 35
        ]); // 97
        
        Office::factory()->create([
            'office_name' => 'Management and Information System Unit',
            'office_abbr' => 'MISU',	
            'parent_id' => 35
        ]); // 98

    //    ____________________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Professional, Technical & Policy Services Unit',
            'office_abbr' => 'PTPSU',	
            'parent_id' => 36
        ]); // 99
        
        Office::factory()->create([
            'office_name' => 'College Printing Press & Services Unit',
            'office_abbr' => 'CPPSU',	
            'parent_id' => 36
        ]); // 100

    //    _______________________________________________________________________
        
        Office::factory()->create([
            'office_name' => 'Accreditation Unit',
            'office_abbr' => 'AU',	
            'parent_id' => 38
        ]); // 101
        
        Office::factory()->create([
            'office_name' => 'Program Compliance & Institutional Sustainability Assessment Unit',
            'office_abbr' => 'PCISAU',	
            'parent_id' => 38
        ]); // 102

    //    ____________________________________________________________________________

        Office::factory()->create([
            'office_name' => 'Document Control Unit',
            'office_abbr' => 'DCU',	
            'parent_id' => 39
        ]); // 103
        
        Office::factory()->create([
            'office_name' => 'Internal Quality Audit Unit',
            'office_abbr' => 'AU',	
            'parent_id' => 39
        ]); // 104
        
        Office::factory()->create([
            'office_name' => 'Risk & Oppurtunities Management Unit',
            'office_abbr' => 'ROMU',	
            'parent_id' => 39
        ]); // 105        
    }
}
