<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SubjectService;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class SubjectServiceTest extends TestCase
{
    protected $subjectRepository;
    protected $subjectService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->subjectRepository = Mockery::mock(SubjectRepositoryInterface::class);
        
        $this->subjectService = new SubjectService($this->subjectRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_subjects()
    {
        // Arrange
        $subjectsData = collect([
            (object) ['id' => 1, 'title' => 'Introdução ao PHP', 'course_id' => 1],
            (object) ['id' => 2, 'title' => 'Laravel Básico', 'course_id' => 2],
        ]);

        $this->subjectRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($subjectsData);

        // Act
        $result = $this->subjectService->getAllSubjects();

        // Assert
        $this->assertEquals($subjectsData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_paginated_subjects()
    {
        // Arrange
        $subjectsData = collect([
            (object) ['id' => 1, 'title' => 'Introdução ao PHP'],
        ]);

        $paginator = new LengthAwarePaginator(
            $subjectsData,
            1,
            10,
            1
        );

        $this->subjectRepository
            ->shouldReceive('paginate')
            ->with(10)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->subjectService->getPaginatedSubjects(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /** @test */
    public function it_can_get_paginated_subjects_with_custom_per_page()
    {
        // Arrange
        $paginator = new LengthAwarePaginator(
            collect([]),
            0,
            20,
            1
        );

        $this->subjectRepository
            ->shouldReceive('paginate')
            ->with(20)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->subjectService->getPaginatedSubjects(20);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(20, $result->perPage());
    }

    /** @test */
    public function it_can_get_a_single_subject()
    {
        // Arrange
        $subjectData = (object) [
            'id' => 1,
            'title' => 'Introdução ao PHP',
            'description' => 'Conceitos básicos de PHP',
            'course_id' => 1,
            'teacher_id' => 1,
        ];

        $this->subjectRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($subjectData);

        // Act
        $result = $this->subjectService->getSubject(1);

        // Assert
        $this->assertEquals($subjectData, $result);
        $this->assertEquals('Introdução ao PHP', $result->title);
    }

    /** @test */
    public function it_returns_null_when_subject_not_found()
    {
        // Arrange
        $this->subjectRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Act
        $result = $this->subjectService->getSubject(999);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_can_create_a_subject()
    {
        // Arrange
        $subjectData = [
            'title' => 'Programação Orientada a Objetos',
            'description' => 'Conceitos de POO em PHP',
            'course_id' => 1,
            'teacher_id' => 1,
        ];

        $createdSubject = (object) array_merge(['id' => 1], $subjectData);

        $this->subjectRepository
            ->shouldReceive('create')
            ->with($subjectData)
            ->once()
            ->andReturn($createdSubject);

        // Act
        $result = $this->subjectService->createSubject($subjectData);

        // Assert
        $this->assertEquals($createdSubject, $result);
        $this->assertEquals('Programação Orientada a Objetos', $result->title);
        $this->assertEquals(1, $result->id);
    }

    /** @test */
    public function it_can_update_a_subject()
    {
        // Arrange
        $subjectId = 1;
        $updateData = [
            'title' => 'POO Avançado',
            'description' => 'Descrição atualizada',
        ];

        $updatedSubject = (object) [
            'id' => $subjectId,
            'title' => 'POO Avançado',
            'description' => 'Descrição atualizada',
            'course_id' => 1,
            'teacher_id' => 1,
        ];

        $this->subjectRepository
            ->shouldReceive('update')
            ->with($subjectId, $updateData)
            ->once()
            ->andReturn($updatedSubject);

        // Act
        $result = $this->subjectService->updateSubject($subjectId, $updateData);

        // Assert
        $this->assertEquals($updatedSubject, $result);
        $this->assertEquals('POO Avançado', $result->title);
    }

    /** @test */
    public function it_can_delete_a_subject()
    {
        // Arrange
        $subjectId = 1;

        $this->subjectRepository
            ->shouldReceive('delete')
            ->with($subjectId)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->subjectService->deleteSubject($subjectId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_subjects_by_course()
    {
        // Arrange
        $courseId = 1;
        $subjectsData = collect([
            (object) ['id' => 1, 'title' => 'Introdução ao PHP', 'course_id' => 1],
            (object) ['id' => 2, 'title' => 'PHP Avançado', 'course_id' => 1],
            (object) ['id' => 3, 'title' => 'Laravel', 'course_id' => 1],
        ]);

        $this->subjectRepository
            ->shouldReceive('getSubjectsByCourse')
            ->with($courseId)
            ->once()
            ->andReturn($subjectsData);

        // Act
        $result = $this->subjectService->getSubjectsByCourse($courseId);

        // Assert
        $this->assertEquals($subjectsData, $result);
        $this->assertCount(3, $result);
        $this->assertEquals(1, $result->first()->course_id);
    }

    /** @test */
    public function it_returns_empty_collection_when_course_has_no_subjects()
    {
        // Arrange
        $courseId = 99;

        $this->subjectRepository
            ->shouldReceive('getSubjectsByCourse')
            ->with($courseId)
            ->once()
            ->andReturn(collect([]));

        // Act
        $result = $this->subjectService->getSubjectsByCourse($courseId);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_can_get_subjects_by_teacher()
    {
        // Arrange
        $teacherId = 1;
        $subjectsData = collect([
            (object) ['id' => 1, 'title' => 'Introdução ao PHP', 'teacher_id' => 1],
            (object) ['id' => 4, 'title' => 'MySQL Básico', 'teacher_id' => 1],
        ]);

        $this->subjectRepository
            ->shouldReceive('getSubjectsByTeacher')
            ->with($teacherId)
            ->once()
            ->andReturn($subjectsData);

        // Act
        $result = $this->subjectService->getSubjectsByTeacher($teacherId);

        // Assert
        $this->assertEquals($subjectsData, $result);
        $this->assertCount(2, $result);
        $this->assertEquals(1, $result->first()->teacher_id);
    }

    /** @test */
    public function it_returns_empty_collection_when_teacher_has_no_subjects()
    {
        // Arrange
        $teacherId = 99;

        $this->subjectRepository
            ->shouldReceive('getSubjectsByTeacher')
            ->with($teacherId)
            ->once()
            ->andReturn(collect([]));

        // Act
        $result = $this->subjectService->getSubjectsByTeacher($teacherId);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_delegates_all_operations_to_repository()
    {
        // Arrange & Act
        $this->subjectRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect([]));

        $this->subjectRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this->subjectRepository
            ->shouldReceive('create')
            ->with(['title' => 'Test'])
            ->once()
            ->andReturn((object) ['id' => 1, 'title' => 'Test']);

        $this->subjectRepository
            ->shouldReceive('getSubjectsByCourse')
            ->with(1)
            ->once()
            ->andReturn(collect([]));

        $this->subjectService->getAllSubjects();
        $this->subjectService->getSubject(1);
        $this->subjectService->createSubject(['title' => 'Test']);
        $this->subjectService->getSubjectsByCourse(1);

        // Assert
        $this->assertTrue(true);
    }
}
