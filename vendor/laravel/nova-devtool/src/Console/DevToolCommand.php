<?php

namespace Laravel\Nova\DevTool\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Orchestra\Testbench\Foundation\Console\Actions\GeneratesFile;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Orchestra\Sidekick\join_paths;
use function Orchestra\Testbench\default_skeleton_path;
use function Orchestra\Testbench\package_path;
use function Orchestra\Testbench\workbench_path;

#[AsCommand(name: 'nova:devtool', description: 'Configure Laravel Nova DevTool')]
class DevToolCommand extends Command implements PromptsForMissingInput
{
    use Concerns\InteractsWithProcess;
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:devtool {action}';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $filesystem, PackageManifest $manifest): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        return match ($action = $this->argument('action')) {
            'setup' => $this->installNovaWorkbench($filesystem, $manifest),
            'install' => $this->installNpmDependencies($filesystem, $manifest),
            'enable-vue-devtool' => $this->enablesVueDevTool($filesystem, $manifest),
            'disable-vue-devtool' => $this->disablesVueDevTool($filesystem, $manifest),
            'tsconfig' => $this->installTypeScriptConfiguration($filesystem, $manifest, confirmation: false),
            default => throw new InvalidArgumentException(sprintf('Unable to handle [%s] action', $action)),
        };
    }

    /**
     * Setup Nova Workbench.
     */
    protected function installNovaWorkbench(Filesystem $filesystem, PackageManifest $manifest): int
    {
        $this->installNoveDevtoolNpmDependencies();

        return $this->call('workbench:install', [
            '--devtool' => true,
        ]);
    }

    /**
     * Install `laravel-nova-devtool` to `package.json`.
     */
    protected function installNoveDevtoolNpmDependencies(): void
    {
        $this->executeCommand([
            'npm set progress=false',
            'npm install --save-dev "vendor/laravel/nova-devtool"',
        ], package_path());
    }

    /**
     * Install `tsconfig.json` configuration.
     */
    protected function installTypeScriptConfiguration(Filesystem $filesystem, PackageManifest $manifest, bool $confirmation = true): int
    {
        (new GeneratesFile(
            filesystem: $filesystem,
            components: $this->components,
            force: false,
            confirmation: $confirmation,
        ))->handle(
            join_paths(__DIR__, 'stubs', 'tsconfig.json'),
            package_path('tsconfig.json')
        );

        return self::SUCCESS;
    }

    /**
     * Install NPM dependencies.
     */
    protected function installNpmDependencies(Filesystem $filesystem, PackageManifest $manifest): int
    {
        $novaTailwindConfigFile = join_paths($manifest->vendorPath, 'laravel', 'nova', 'tailwind.config.js');

        if (str_starts_with($novaTailwindConfigFile, default_skeleton_path())) {
            $novaTailwindConfigFile = './'.ltrim(Str::after($novaTailwindConfigFile, default_skeleton_path()), DIRECTORY_SEPARATOR);
        } elseif (str_starts_with($novaTailwindConfigFile, package_path())) {
            $novaTailwindConfigFile = './'.ltrim(Str::after($novaTailwindConfigFile, package_path()), DIRECTORY_SEPARATOR);
        }

        $dependencies = multiselect(
            label: 'Dependencies to install?',
            options: [
                '@inertiajs/vue3',
                'axios',
                'lodash',
                'tailwindcss',
                'vue',
                'vuex',
            ],
            default: [],
        );

        if (empty($dependencies)) {
            $this->components->info('Nothing to install');

            return self::SUCCESS;
        }

        $this->installNoveDevtoolNpmDependencies();

        $this->executeCommand([
            'npm set progress=false',
            'npm install --save-dev '.implode(' ', $dependencies),
        ], package_path());

        if (in_array('tailwindcss', $dependencies)) {
            $this->executeCommand([
                'npm set progress=false',
                'npm install --save-dev '.implode(' ', ['@tailwindcss/container-queries', '@tailwindcss/typography']),
            ], package_path());

            $filesystem->copy(join_paths(__DIR__, 'stubs', 'postcss.config.js'), package_path('postcss.config.js'));
            $filesystem->copy(join_paths(__DIR__, 'stubs', 'tailwind.config.js'), package_path('tailwind.config.js'));
            $filesystem->replaceInFile([
                '{{novaTailwindConfigFile}}',
            ], [
                str_replace(DIRECTORY_SEPARATOR, '/', $novaTailwindConfigFile),
            ], package_path('tailwind.config.js'));
        }

        return $this->installTypeScriptConfiguration($filesystem, $manifest, confirmation: true);
    }

    /**
     * Enables Vue DevTool.
     */
    protected function enablesVueDevTool(Filesystem $filesystem, PackageManifest $manifest): int
    {
        return $this->call('nova:enable-vue-devtool', ['--force' => true]);
    }

    /**
     * Disables Vue DevTool.
     */
    protected function disablesVueDevTool(Filesystem $filesystem, PackageManifest $manifest): int
    {
        return $this->call('nova:disable-vue-devtool', ['--force' => true]);
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, \Closure>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'action' => fn () => select(
                label: 'Which action to be executed?',
                options: array_filter([
                    'setup' => is_dir(workbench_path()) ? null : 'Setup Nova Workbench',
                    'install' => 'Install NPM Dependencies',
                    'enable-vue-devtool' => 'Enable Vue DevTool',
                    'disable-vue-devtool' => 'Disable Vue DevTool',
                    'tsconfig' => is_file(package_path('tsconfig.json')) ? null : 'Install `tsconfig.json` for Nova',
                ]),
                default: 'owner'
            ),
        ];
    }
}
