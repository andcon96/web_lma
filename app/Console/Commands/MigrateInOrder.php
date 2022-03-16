<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate_in_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        $migrations = [
            '2019_12_14_000001_create_personal_access_tokens_table.php',
            '2021_12_14_000002_create_departments_table.php',
            '2021_12_14_000003_create_roles_table.php',
            '2021_12_15_000009_create_role_types_table.php',
            '2021_12_14_000004_create_suppliers_table.php',
            '2021_12_14_000005_create_users_table.php',
            '2021_12_14_000006_create_password_resets_table.php',
            '2021_12_14_000007_create_failed_jobs_table.php',
            '2021_12_14_000008_create_notifications_table.php',
            '2021_12_20_024707_create_domains_table.php',
            '2021_12_20_023837_create_sites_table.php',
            '2021_12_14_000011_create_qxwsa_table.php',
            '2021_12_14_000032_create_transaction_table.php',
            '2021_12_14_000033_create_budgeting_app_table.php',
            '2021_12_20_045006_create_item_inventory_controls_table.php',
            '2021_12_20_070155_create_item_inventory_masters_table.php',
            '2021_12_21_042446_create_items_conversion_table.php',
            '2021_12_21_045414_create_items_um_table.php',
            '2021_12_27_040321_create_supplier_item_relation_table.php',
            '2021_12_20_041453_create_po_approver_table.php',
            '2022_01_17_152910_create_prefix_table.php',
            '2021_12_22_040351_create_rfps_approval_table.php',
            '2021_12_22_085649_create_rfp_masters_table.php',
            '2021_12_24_032142_create_rfp_dets_table.php',
            '2021_12_27_061910_create_rfp_histories_table.php',
            '2021_12_27_071600_create_rfp_trans_approval_table.php',
            '2021_12_27_082843_create_companies_table.php',
            '2021_12_24_024432_create_rfq_masters_table.php',
            '2021_12_27_091140_create_rfq_details_table.php',
            '2021_12_27_094249_create_rfq_history_table.php',
            '2021_12_30_162220_create_rfp_trans_approval_hist_table.php',
            '2022_01_03_165429_create_purchase_plan_masters_table.php',
            '2022_01_04_094545_create_purchase_plan_details_table.php',
            '2022_01_05_105940_create_purchase_plan_temp_table.php',
            '2022_01_06_093857_create_inventory_masters_table.php',
            '2022_01_06_103800_create_inventory_details_table.php',
            '2022_01_06_160423_create_tr_histories_table.php',
            '2022_01_07_113351_create_po_masters_table.php',
            '2022_01_07_120933_create_po_details_table.php',
            '2022_01_07_134656_create_po_histories_table.php',
            '2022_01_07_143547_create_po_trans_approval_table.php',
            '2022_01_07_144944_create_po_trans_approval_hist_table.php',
            '2022_01_10_144940_create_surat_jalan_table.php',
            '2022_01_19_101252_create_po_receipts_table.php',
        ];

        $this->call('migrate:reset');

        foreach ($migrations as $migration)
        {
            $basePath       = 'database/migrations/';
            $migrationName  = trim($migration);
            $path           = $basePath . $migrationName;

            $this->call('migrate', [
                '--path' => $path
            ]);
        }
        
        $this->call('db:seed');
    }
}
