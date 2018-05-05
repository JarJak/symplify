<?php declare(strict_types=1);

namespace Symplify\Statie\Renderable\File;

use Symfony\Component\Finder\SplFileInfo;
use Symplify\Statie\Exception\Configuration\GeneratorException;
use Symplify\Statie\Utils\PathAnalyzer;

final class GeneratorFileFactory
{
    /**
     * @var PathAnalyzer
     */
    private $pathAnalyzer;

    public function __construct(PathAnalyzer $pathAnalyzer)
    {
        $this->pathAnalyzer = $pathAnalyzer;
    }

    /**
     * @param SplFileInfo[] $fileInfos
     * @return AbstractGeneratorFile[]
     */
    public function createFromFileInfosAndClass(array $fileInfos, string $class): array
    {
        $objects = [];

        $this->ensureIsAbstractGeneratorFile($class);

        foreach ($fileInfos as $fileInfo) {
            $generatorFile = $this->createFromClassNameAndFileInfo($class, $fileInfo);

            // @todo make sure is unique
            $objects[$generatorFile->getId()] = $generatorFile;
        }

        return $objects;
    }

    private function createFromClassNameAndFileInfo(string $className, SplFileInfo $fileInfo): AbstractGeneratorFile
    {
        $dateTime = $this->pathAnalyzer->detectDate($fileInfo);
        if ($dateTime) {
            $filenameWithoutDate = $this->pathAnalyzer->detectFilenameWithoutDate($fileInfo);
        } else {
            $filenameWithoutDate = $fileInfo->getBasename('.' . $fileInfo->getExtension());
        }

        // @todo get ID from the content,
        // Guard
        // throw exception otherwise

        return new $className(
            $fileInfo,
            $fileInfo->getRelativePathname(),
            $fileInfo->getPathname(),
            $filenameWithoutDate,
            $dateTime
        );
    }

    private function ensureIsAbstractGeneratorFile(string $class): void
    {
         if (is_a($class, AbstractGeneratorFile::class, true)) {
             return;
         }

         throw new GeneratorException(sprintf(
            '"%s" must inherit from "%s"',
            $class,
             AbstractGeneratorFile::class
         ));
    }
}
