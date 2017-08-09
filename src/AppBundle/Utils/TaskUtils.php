<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 08.08.2017
 * Time: 19:55
 */

namespace AppBundle\Utils;

use AppBundle\Entity\RecurringTask;
use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

class TaskUtils
{

    /** @var  EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function getNow()
    {
        return (new \DateTime())->setTime(0, 0, 0);
    }

    public function generateTasks(User $user = null)
    {
        $tasksCreated = 0;

        if ($user) {
            $rTasks = $this->em->getRepository('AppBundle:RecurringTask')->findBy(array(
                'owner' => $user,
            ));
        } else {
            $rTasks = $this->em->getRepository('AppBundle:RecurringTask')->findAll();
        }

        $now = $this->getNow();

        /** @var RecurringTask $rTask */
        foreach ($rTasks as $rTask) {
            $startDate = $rTask->getStartDate();
            if (
            $existedTasks = $this->em->getRepository('AppBundle:Task')->createQueryBuilder('t')
                ->where('t.recurringTask = :rTask')
                ->setParameter('rTask', $rTask)
                ->orderBy('t.date', 'DESC')->getQuery()->getResult()
            ) {
                $startDate = $existedTasks[0]->getDate();
            }

            switch ($rTask->getRepeats()) {

                case RecurringTask::REPEATS_WEEKLY:

                    $iterationDate = clone $startDate;
                    $intervalEnd = clone $iterationDate;

                    while ($intervalEnd <= $now) {

                        $intervalStart = (clone $iterationDate)->modify('-' . ($iterationDate->format('w') - 1) . ' days');
                        $intervalEnd = (clone $iterationDate)->modify('+' . (7 - $iterationDate->format('w')) . ' days');

                        $this->dumpInterval($intervalStart, $intervalEnd);

                        if (count($rTask->getRepeatDays()) > 0) {
                            foreach ($rTask->getRepeatDays() as $weekDayNum) {
                                $newTaskDate = (clone $intervalStart)->modify('+' . $weekDayNum . ' days');
                                $tasksCreated += $this->createTask($rTask, $newTaskDate);
                            }
                        } else {
                            $newTaskDate = (clone $intervalStart)->modify('+' . ($startDate->format('w') - 1) . ' days');
                            $tasksCreated += $this->createTask($rTask, $newTaskDate);
                        }

                        $iterationDate = $iterationDate->modify('+' . ($rTask->getIntervalWeek() * 7) . ' days');
                    }

                    break;

                case RecurringTask::REPEATS_MONTHLY:

                    $interval = $rTask->getIntervalMonth();

                    $iterationDate = (clone $startDate)->modify('first day of this month');
                    $intervalEnd = clone $iterationDate;

                    while ($now >= $intervalEnd) {

                        $intervalStart = (clone $iterationDate)->modify('first day of this month');
                        $intervalEnd = (clone $iterationDate)->modify('first day of next month')->modify('-1 day');

                        $this->dumpInterval($intervalStart, $intervalEnd);

                        if ($rTask->getRepeatMonth() == RecurringTask::REPEAT_MONTH_DAY_OF_MONTH) {
                            $newTaskDate = (clone $intervalStart)->modify('+' . ($startDate->format('d') - 1) . ' days');

                            if ($newTaskDate >= $intervalStart && $newTaskDate <= $intervalEnd) {
                                $tasksCreated += $this->createTask($rTask, $newTaskDate);
                            }
                        }
                        if ($rTask->getRepeatMonth() == RecurringTask::REPEAT_MONTH_DAY_OF_WEEK) {
                            $weekNumber = ($this->weekOfMonth($startDate) - 1);
                            $newTaskDate = (clone $intervalStart)->modify('first ' . $startDate->format('l') . ' of this month');
                            $newTaskDate = $newTaskDate->modify('+' . ($weekNumber * 7) . ' days');

                            if ($newTaskDate >= $intervalStart && $newTaskDate <= $intervalEnd) {
                                $tasksCreated += $this->createTask($rTask, $newTaskDate);
                            }

                        }

                        for ($i = 1; $i <= $interval; $i++) {
                            $iterationDate = $iterationDate->modify('first day of next month');
                        }
                    }

                    break;

                case RecurringTask::REPEATS_YEARLY:

                    $interval = $rTask->getIntervalYear();

                    $iterationDate = (clone $startDate)->modify('first day of this year');
                    $intervalEnd = clone $iterationDate;

                    while ($intervalEnd <= $now) {

                        $intervalStart = (clone $iterationDate)->modify('first day of this year');
                        $intervalEnd = (clone $iterationDate)->modify('first day of next year')->modify('-1 day');

                        $this->dumpInterval($intervalStart, $intervalEnd);
                        $newTaskDate = (clone $intervalStart)->modify('+' . $startDate->format('z') . ' days');
                        $tasksCreated += $this->createTask($rTask, $newTaskDate);

                        for ($i = 1; $i <= $interval; $i++) {
                            $iterationDate = $iterationDate->modify('first day of next year');
                        }
                    }

                    break;

                case RecurringTask::REPEATS_ONCE:

                    $newTaskDate = clone $startDate;
                    $tasksCreated += $this->createTask($rTask, $newTaskDate);

                    break;

            }

        }

        $this->em->flush();

        return $tasksCreated;
    }

    protected function createTask(RecurringTask $recurringTask, \DateTime $taskDate)
    {
        if ($taskDate <= $this->getNow() && !$this->em->getRepository('AppBundle:Task')->findBy(array(
                'date' => $taskDate,
                'recurringTask' => $recurringTask
            ))) {

            //echo 'Create task "' . $recurringTask . '" at ' . $taskDate->format('Y-m-d') . PHP_EOL;

            $task = new Task();
            $task->setOwner($recurringTask->getOwner())
                ->setDate($taskDate)
                ->setRecurringTask($recurringTask)
                ->setCompleted(false);

            $this->em->persist($task);

            return 1;

        }

        return 0;
    }

    protected function dumpInterval(\DateTime $intervalStart, \DateTime $intervalEnd)
    {
        //echo '-------------------------------' . PHP_EOL;
        //echo $intervalStart->format('Y-m-d') . ' - ' . $intervalEnd->format('Y-m-d') . PHP_EOL;
    }

    protected function weekOfMonth(\DateTime $date)
    {
        $firstOfMonth = strtotime($date->format("Y-m-01"));
        return intval($date->format("W")) - intval(date("W", $firstOfMonth)) + 1;
    }

}
