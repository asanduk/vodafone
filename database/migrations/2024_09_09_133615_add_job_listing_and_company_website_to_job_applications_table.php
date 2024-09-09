<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobListingAndCompanyWebsiteToJobApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('job_listing_url')->nullable(); // İş ilanı linki
            $table->string('company_website_url')->nullable(); // Firma web sitesi
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('job_listing_url');
            $table->dropColumn('company_website_url');
        });
    }
}

