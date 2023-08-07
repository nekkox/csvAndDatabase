<?php
require_once "env.php";

//creating tables
$tableCreater = <<<EOT
CREATE TABLE dept
(deptno int(4) NOT NULL,
dname varchar(14),
loc varchar(13),
CONSTRAINT dept_pkey PRIMARY KEY (deptno)
);
CREATE TABLE emp
(
empno int(4) NOT NULL,
ename varchar(10),
job varchar(9),
mgr int(4),
hiredate timestamp,
sal double,
comm double,
deptno int(4),
CONSTRAINT emp_pkey PRIMARY KEY (empno),
CONSTRAINT fk_deptno FOREIGN KEY (deptno)
REFERENCES dept (deptno) ON DELETE CASCADE);
CREATE UNIQUE INDEX pk_emp on emp(empno);
CREATE INDEX emp_deptno on emp(deptno);
CREATE UNIQUE INDEX pk_dept on dept(deptno);
EOT;


function create_insert_stmt($table, $ncols)
{
    $stmt = "insert into $table values(";
    foreach (range(1, $ncols) as $i) {
        $stmt .= "?,";
    }
    $stmt = preg_replace("/,$/", ")", $stmt);
    return $stmt;
}

function insert_into_table_from_csvFile($tableName, $fileName)
{
    $rownum = 0;
    global $pdo;
    $res = $pdo->prepare("select * from $tableName");
    $res->execute();
    $ncols = $res->columnCount();
    $insert = create_insert_stmt($tableName, $ncols);
    $res = $pdo->prepare($insert);

    $fp = new SplFileObject($fileName, "r");
    $pdo->beginTransaction();

    while ($row = $fp->fgetcsv()) {
        if (strlen(implode("", $row)) == 0)  {
            continue;
        }
        $res->execute($row);
        $rownum++;
    }
    $pdo->commit();
    print "$rownum rows inserted into $tableName.\n";
}
