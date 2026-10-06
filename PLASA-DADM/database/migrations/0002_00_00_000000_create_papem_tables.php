<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS documents (
                id SERIAL PRIMARY KEY,
                title TEXT NOT NULL,
                url TEXT NOT NULL,
                type TEXT NOT NULL,
                category TEXT,
                unit TEXT CHECK (unit IN (\'EAGM\', \'1DN\')),
                tags TEXT[] NOT NULL DEFAULT ARRAY[]::TEXT[],
                active BOOLEAN NOT NULL DEFAULT TRUE,
                upload_date TIMESTAMP DEFAULT NOW()
            );

            CREATE TABLE IF NOT EXISTS notices (
                id SERIAL PRIMARY KEY,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                priority TEXT NOT NULL CHECK (priority IN (\'high\', \'medium\', \'low\')),
                start_date TIMESTAMP NOT NULL,
                end_date TIMESTAMP NOT NULL,
                active BOOLEAN NOT NULL DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            );

            CREATE TABLE IF NOT EXISTS military_personnel (
                id SERIAL PRIMARY KEY,
                name TEXT NOT NULL,
                rank TEXT NOT NULL,
                type TEXT NOT NULL CHECK (type IN (\'officer\', \'master\')),
                specialty TEXT,
                full_rank_name TEXT NOT NULL,
                active BOOLEAN NOT NULL DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            );

            CREATE TABLE IF NOT EXISTS duty_assignments (
                id SERIAL PRIMARY KEY,
                officer_name TEXT NOT NULL,
                officer_rank TEXT,
                master_name TEXT NOT NULL,
                master_rank TEXT,
                valid_from TIMESTAMP NOT NULL DEFAULT NOW(),
                updated_at TIMESTAMP NOT NULL DEFAULT NOW()
            );

            CREATE INDEX IF NOT EXISTS duty_assignments_valid_from_idx
                ON duty_assignments (valid_from DESC, updated_at DESC);

            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = NOW();
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            DROP TRIGGER IF EXISTS update_notices_updated_at ON notices;
            CREATE TRIGGER update_notices_updated_at
                BEFORE UPDATE ON notices
                FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

            DROP TRIGGER IF EXISTS update_military_personnel_updated_at ON military_personnel;
            CREATE TRIGGER update_military_personnel_updated_at
                BEFORE UPDATE ON military_personnel
                FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

            DROP TRIGGER IF EXISTS update_duty_assignments_updated_at ON duty_assignments;
            CREATE TRIGGER update_duty_assignments_updated_at
                BEFORE UPDATE ON duty_assignments
                FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

            CREATE OR REPLACE FUNCTION notify_documents_changed()
            RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'documents_changed\', json_build_object(
                    \'id\', COALESCE(NEW.id, OLD.id),
                    \'operation\', TG_OP
                )::text);
                RETURN COALESCE(NEW, OLD);
            END;
            $$ LANGUAGE plpgsql;

            DROP TRIGGER IF EXISTS documents_notify_trigger ON documents;
            CREATE TRIGGER documents_notify_trigger
                AFTER INSERT OR UPDATE OR DELETE ON documents
                FOR EACH ROW EXECUTE FUNCTION notify_documents_changed();

            CREATE OR REPLACE FUNCTION notify_duty_assignments_changed()
            RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'duty_assignments_changed\', json_build_object(
                    \'id\', COALESCE(NEW.id, OLD.id),
                    \'operation\', TG_OP
                )::text);
                RETURN COALESCE(NEW, OLD);
            END;
            $$ LANGUAGE plpgsql;

            DROP TRIGGER IF EXISTS duty_assignments_notify_trigger ON duty_assignments;
            CREATE TRIGGER duty_assignments_notify_trigger
                AFTER INSERT OR UPDATE OR DELETE ON duty_assignments
                FOR EACH ROW EXECUTE FUNCTION notify_duty_assignments_changed();
        ');
    }

    public function down(): void
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS duty_assignments_notify_trigger ON duty_assignments;
            DROP TRIGGER IF EXISTS documents_notify_trigger ON documents;
            DROP TRIGGER IF EXISTS update_duty_assignments_updated_at ON duty_assignments;
            DROP TRIGGER IF EXISTS update_military_personnel_updated_at ON military_personnel;
            DROP TRIGGER IF EXISTS update_notices_updated_at ON notices;
            DROP FUNCTION IF EXISTS notify_duty_assignments_changed();
            DROP FUNCTION IF EXISTS notify_documents_changed();
            DROP FUNCTION IF EXISTS update_updated_at_column();
            DROP TABLE IF EXISTS duty_assignments;
            DROP TABLE IF EXISTS military_personnel;
            DROP TABLE IF EXISTS notices;
            DROP TABLE IF EXISTS documents;
        ');
    }
};
