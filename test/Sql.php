<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// sql
class Sql extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// isQuery
		\assert(Base\Sql::isQuery('select'));
		\assert(!Base\Sql::isQuery('SELECTz'));

		// isQuote
		\assert(Base\Sql::isQuote("'test'"));
		\assert(!Base\Sql::isQuote("'test"));

		// hasTickOrSpace
		\assert(Base\Sql::hasTickOrSpace('`test bla'));
		\assert(Base\Sql::hasTickOrSpace('test bla'));
		\assert(!Base\Sql::hasTickOrSpace('test'));

		// isTick
		\assert(!Base\Sql::isTick("test"));
		\assert(Base\Sql::isTick("`test`"));
		\assert(Base\Sql::isTick("test.`test`"));

		// isParenthesis
		\assert(Base\Sql::isParenthesis('('));
		\assert(Base\Sql::isParenthesis('(',true));
		\assert(!Base\Sql::isParenthesis(')',true));
		\assert(Base\Sql::isParenthesis(')',false));

		// isKey
		\assert(Base\Sql::isKey('unique'));
		\assert(!Base\Sql::isKey('uniquez'));

		// isColType
		\assert(!Base\Sql::isColType('unique'));
		\assert(Base\Sql::isColType('tinyint'));
		\assert(Base\Sql::isColType('mediumint'));

		// isWhereSymbol
		\assert(Base\Sql::isWhereSymbol('!'));

		// isWhereSeparator
		\assert(Base\Sql::isWhereSeparator('AND'));
		\assert(!Base\Sql::isWhereSeparator('('));

		// isWhereTwo
		\assert(Base\Sql::isWhereTwo(true));
		\assert(Base\Sql::isWhereTwo('null'));
		\assert(!Base\Sql::isWhereTwo('like'));
		\assert(Base\Sql::isWhereTwo(234));

		// isOrderDirection
		\assert(Base\Sql::isOrderDirection('asc'));
		\assert(!Base\Sql::isOrderDirection('ascz'));

		// isReturnSelect
		$select = Base\Sql::select("*","table",3);
		$update = Base\Sql::update("table",['name'=>'bla'],3);
		\assert(!Base\Sql::isReturnSelect($select));
		\assert(Base\Sql::isReturnSelect($update));

		// isReturnRollback
		$select = Base\Sql::select("*","table",3);
		$update = Base\Sql::update("table",['name'=>'bla'],3);
		\assert(!Base\Sql::isReturnRollback($select));
		\assert(Base\Sql::isReturnRollback($update));

		// isReturnTableId
		\assert(Base\Sql::isReturnTableId($select));
		\assert(Base\Sql::isReturnTableId($update));

		// hasDot
		\assert(Base\Sql::hasDot("test.`test`"));
		\assert(!Base\Sql::hasDot("test"));

		// hasQueryClause
		\assert(Base\Sql::hasQueryClause('select','table'));
		\assert(Base\Sql::hasQueryClause('select','what'));
		\assert(!Base\Sql::hasQueryClause('select','james'));

		// getQueryTypes
		\assert(\count(Base\Sql::getQueryTypes()) === 9);

		// getQueryRequired
		\assert(Base\Sql::getQueryRequired('select') === ['what','table']);
		\assert(Base\Sql::getQueryRequired('update') === ['table','updateSet','where']);
		\assert(Base\Sql::getQueryRequired('updatez') === null);

		// getKeyWord
		\assert(Base\Sql::getKeyWord('unique') === "UNIQUE KEY");
		\assert(Base\Sql::getKeyWord('uniquez') === null);

		// getColTypeAttr
		\assert(\count(Base\Sql::getColTypeAttr('varchar')) === 2);
		\assert(Base\Sql::getColTypeAttr('varcharz') === null);

		// functionFormat
		\assert(Base\Sql::functionFormat('lower') === 'LOWER');

		// getWhatFunction
		\assert(\count(Base\Sql::getWhatFunction('distinct')) === 2);
		\assert(Base\Sql::getWhatFunction('SUM')['parenthesis'] === true);

		// getWhereSymbol
		\assert(Base\Sql::getWhereSymbol('!') === '!=');
		\assert(Base\Sql::getWhereSymbol('!=') === '!=');

		// getWhereMethod
		\assert(Base\Sql::getWhereMethod('findInSet') === [Base\Sql::class,'whereFind']);
		\assert(Base\Sql::getWhereMethod('findz') === null);

		// getWhereSeparator
		\assert(Base\Sql::getWhereSeparator() === "AND");
		\assert(Base\Sql::getWhereSeparator("or") === "OR");
		\assert(Base\Sql::getWhereSeparator("AnD") === "AND");
		\assert(Base\Sql::getWhereSeparator("&&") === "&&");
		\assert(Base\Sql::getWhereSeparator("xor") === "XOR");

		// getOrderDirection
		\assert(Base\Sql::getOrderDirection('desc') === 'DESC');
		\assert(Base\Sql::getOrderDirection() === 'ASC');
		\assert(Base\Sql::getOrderDirection('ASC') === 'ASC');
		\assert(Base\Sql::getOrderDirection(true) === 'ASC');

		// invertOrderDirection
		\assert(Base\Sql::invertOrderDirection('desc') === 'ASC');
		\assert(Base\Sql::invertOrderDirection() === 'DESC');
		\assert(Base\Sql::invertOrderDirection(true) === 'DESC');

		// getOrderMethod
		\assert(Base\Sql::getOrderMethod('find') === [Base\Sql::class,'orderFind']);
		\assert(Base\Sql::getOrderMethod('findz') === null);

		// getSetMethod
		\assert(Base\Sql::getSetMethod('replace') === [Base\Sql::class,'setReplace']);

		// getQueryWord
		\assert(Base\Sql::getQueryWord('select') === 'SELECT');
		\assert(Base\Sql::getQueryWord('select','table') === 'FROM');
		\assert(Base\Sql::getQueryWord('select','where') === 'WHERE');
		\assert(Base\Sql::getQueryWord('drop','table',['dropExists'=>true]) === "TABLE IF EXISTS");
		\assert(Base\Sql::getQueryWord('drop','table') === "TABLE");
		\assert(Base\Sql::getQueryWord('create','table',['createNotExists'=>true]) === "TABLE IF NOT EXISTS");

		// getReturn
		\assert(Base\Sql::getReturn() === ['sql'=>'']);
		\assert(Base\Sql::getReturn(['bla'=>'ok']) === ['sql'=>'']);
		\assert(Base\Sql::getReturn(['sql'=>'ok']) === ['sql'=>'ok']);

		// returnMerge
		\assert(\count(Base\Sql::returnMerge(['sql'=>'test','prepare'=>['test'=>2],'james'=>true],['sql'=>'test2','prepare'=>['test'=>4,'test2'=>3]])['prepare']) === 2);
		\assert(Base\Sql::returnMerge(['sql'=>'test','prepare'=>['test'=>2],'james'=>true],['sql'=>'test2','prepare'=>['test'=>4,'test2'=>3]])['sql'] === "testtest2");

		// tick
		\assert(Base\Sql::tick("test") === "`test`");
		\assert(Base\Sql::tick("test.test2") === "test.`test2`");
		\assert(Base\Sql::tick("`test`.`test`") === "`test`.`test`");
		\assert(Base\Sql::tick("test_[lang]") === "`test_en`");
		\assert(Base\Sql::tick("test",['binary'=>true]) === "BINARY `test`");
		\assert(Base\Sql::tick("test",['function'=>'LOWER']) === "LOWER(`test`)");
		\assert(Base\Sql::tick("test",['function'=>'LOWER','binary'=>true]) === "BINARY LOWER(`test`)");
		\assert(Base\Sql::tick("(SELECT * FROM table)") === '(SELECT * FROM table)');
		\assert(Base\Sql::tick("@rownum := @rownum + 1") === '@rownum := @rownum + 1');

		// untick
		\assert(Base\Sql::untick("test.`test`") === "test.test");
		\assert(Base\Sql::untick("`test`.`test`") === "test.test");
		\assert(Base\Sql::untick("`test`") === "test");

		// quote
		\assert(Base\Sql::quote("test") === "'test'");
		\assert(Base\Sql::quote(2) === 2);
		\assert(Base\Sql::quote('test',[Base\Str::class,'upper']) === 'TEST');

		// quoteSet
		\assert(Base\Sql::quoteSet(['test',2,3]) === "'test',2,3");
		\assert(Base\Sql::quoteSet(['test','bla'],[Base\Str::class,'upper']) === "TEST,BLA");

		// unquote
		\assert(Base\Sql::unquote("'test'") === 'test');

		// parenthesis
		\assert(Base\Sql::parenthesis("test") === "(test)");
		\assert(Base\Sql::parenthesis("") === "");

		// comma
		\assert(Base\Sql::comma("test") === ', ');
		\assert(Base\Sql::comma("test",false) === ',');
		\assert(Base\Sql::comma("",false) === '');

		// whereSeparator
		\assert(Base\Sql::whereSeparator("z") === " AND ");
		\assert(Base\Sql::whereSeparator("z","or") === " OR ");
		\assert(Base\Sql::whereSeparator("","or") === "");
		\assert(Base\Sql::whereSeparator(null,"or",false) === "OR");

		// boolNull
		\assert(Base\Sql::boolNull(true) === 1);
		\assert(Base\Sql::boolNull(null) === 'NULL');

		// prepare
		\assert(\count(Base\Sql::prepare()) === 2);

		// prepareValue
		\assert(Base\Sql::prepareValue(true) === 1);
		\assert(Base\Sql::prepareValue(false) === 0);
		\assert(Base\Sql::prepareValue(null) === 'NULL');
		\assert(Base\Sql::prepareValue([1,2,3]) === "1,2,3");
		\assert(Base\Sql::prepareValue(['test'=>2,'james'=>3]) === "{\"test\":2,\"james\":3}");
		\assert(\strlen(Base\Sql::prepareValue(new \Datetime("now"))) > 100);

		// value
		\assert(\strlen(Base\Sql::value("test",[],Base\Sql::option())['sql']) >= 8);
		\assert(\count(Base\Sql::value("test",['sql'=>':test_0','prepare'=>['test_0'=>2]],Base\Sql::option())['prepare']) === 2);
		\assert(Base\Sql::value('test',[],['quoteCallable'=>[Base\Str::class,'upper']])['sql'] === 'TEST');
		\assert(Base\Sql::value('test.bla',[],['quote'=>false])['sql'] === 'test.bla');
		\assert(Base\Sql::value(null,[])['sql'] === 'NULL');
		\assert(Base\Sql::value(true,[])['sql'] === '1');
		\assert(Base\Sql::value(false,[])['sql'] === '0');
		\assert(Base\Sql::value(1.2,[])['sql'] === '1.2');
		\assert(Base\Sql::value(1,[])['sql'] === '1');
		\assert(Base\Sql::value("test.james",[],['tick'=>true])['sql'] === 'test.`james`');
		\assert(\count(Base\Sql::value("james",['sql'=>'','prepare'=>[1,2,3]],['prepare'=>true])['prepare']) === 4);
		\assert(Base\Sql::value('james@landre_ok',null,['quoteChar'=>['@','_']])['sql'] === "'james\@landre\_ok'");
		\assert(\current(Base\Sql::value('james@landre_ok',null,Base\Sql::option(['quoteChar'=>['@','_']]))['prepare']) === "james\@landre\_ok");

		// valueSet
		\assert(\count(Base\Sql::valueSet([1,2,'string',3],[],['prepare'=>true])['prepare']) === 1);
		\assert(\strlen(Base\Sql::valueSet([1,2,'string',3],[],['prepare'=>true])['sql']) >= 17);

		// makeSet
		\assert(Base\Sql::makeSet([1,2,3,'TEST']) === "1,2,3,TEST");

		// makeDefault

		// addDefault
		\assert(Base\Sql::addDefault(null) === [true]);
		\assert(Base\Sql::addDefault(['test'=>true,true]) === ['test'=>true,true]);
		\assert(Base\Sql::addDefault(['test'=>true] === ['test'=>true,true]));

		// removeDefault
		\assert(Base\Sql::removeDefault(null) === []);
		\assert(Base\Sql::removeDefault(['test'=>true,true]) === ['test'=>true]);

		// sql

		// what
		\assert(Base\Sql::what('*')['sql'] === '*');
		\assert(Base\Sql::what(['james.test','ok','what','james'=>'ok'])['sql'] === "james.`test`, `ok`, `what`, `ok` AS `james`");
		\assert(Base\Sql::what(['ok.lol','james.test'=>['test','distinct']])['sql'] === 'ok.`lol`, DISTINCT `test` AS james.`test`');
		\assert(Base\Sql::what('SUM(`test`), SUM(`bla`) AS james.`test`')['sql'] === 'SUM(`test`), SUM(`bla`) AS james.`test`');
		\assert(Base\Sql::what('id')['sql'] === 'id');
		\assert(Base\Sql::what(['id','*','test.james'])['sql'] === '`id`, *, test.`james`');
		\assert(Base\Sql::what(['id','name_[lang]','key_[lang]'])['sql'] === '`id`, `name_en`, `key_en`');
		\assert(Base\Sql::what(true,Base\Sql::option())['sql'] === "*");
		\assert(Base\Sql::what([true,'james.sql',true],Base\Sql::option())['sql'] === '*, james.`sql`, *');
		\assert(Base\Sql::what([['test','distinct','ok'],['james','distinct','what']])['sql'] === 'DISTINCT `test` AS `ok`, DISTINCT `james` AS `what`');
		\assert(Base\Sql::what([['test','ok'],['test2','ok2']])['sql'] === '`test` AS `ok`, `test2` AS `ok2`');
		\assert(Base\Sql::what(['distinct()'=>'test'])['sql'] === 'DISTINCT `test`');
		\assert(Base\Sql::what([['what','sum()']])['sql'] === 'SUM(`what`)');
		\assert(Base\Sql::what([['what','sum()']])['cast'] === true);
		\assert(empty(Base\Sql::what([['what','sum']])['cast']));
		\assert(Base\Sql::what([["(SELECT * FROM TABLE)",'test']])['sql'] === '(SELECT * FROM TABLE) AS `test`');

		// whatPrepare
		\assert(Base\Sql::whatPrepare(['test','ok','*']) === [['test'],['ok'],['*']]);
		\assert(Base\Sql::whatPrepare(['test'=>'james']) === [['james','test']]);
		\assert(Base\Sql::whatPrepare(['test'=>['ok','james']]) === [['ok','james','test']]);
		\assert(Base\Sql::whatPrepare([['ok','james']]) === [['ok','james']]);

		// whatOne
		\assert(Base\Sql::whatOne('*')['sql'] === '*');
		\assert(Base\Sql::whatOne('test')['sql'] === '`test`');

		// whatTwo
		\assert(Base\Sql::whatTwo('test','james')['sql'] === '`test` AS `james`');
		\assert(Base\Sql::whatTwo('test','sum()')['sql'] === 'SUM(`test`)');

		// whatThree
		\assert(Base\Sql::whatThree('test','sum','test')['sql'] === "SUM(`test`)");
		\assert(Base\Sql::whatThree('test','sum','lol')['sql'] === 'SUM(`test`) AS `lol`');
		\assert(Base\Sql::whatThree('test','distinct','lol')['sql'] === 'DISTINCT `test` AS `lol`');
		\assert(Base\Sql::whatThree('test','sum()','lol')['sql'] === 'SUM(`test`) AS `lol`');
		\assert(Base\Sql::whatThree('test','sum()','lol')['cast'] === true);

		// whatFromWhere
		\assert(Base\Sql::whatFromWhere(['test'=>2,['id','in',[2,3,4]],'id'=>4],'t') === ['t.test','t.id']);
		\assert(Base\Sql::whatFromWhere('test') === ['*']);

		// table
		\assert(Base\Sql::table('test')['sql'] === '`test`');
		\assert(Base\Sql::table('test')['table'] === 'test');
		\assert(Base\Sql::table("`test`")['table'] === 'test');

		// join
		\assert(\strlen(Base\Sql::join(['table'=>'james','on'=>['active'=>1,'james.tst'=>'deux']],Base\Sql::option())['sql']) >= 51);
		\assert(Base\Sql::join(['test','on'=>['active'=>4]],['table'=>'james'])['sql'] === '`test` ON(`active` = 4)');
		\assert(Base\Sql::join(['on'=>['active'=>3],'table'=>'LOL'],['table'=>'james'])['sql'] === '`LOL` ON(`active` = 3)');
		\assert(Base\Sql::join(['table'=>'lol','on'=>[['lol.id','`=`','session.id']]],Base\Sql::option())['sql'] === '`lol` ON(lol.`id` = session.`id`)');
		\assert(Base\Sql::join(['table'=>'lol','on'=>[['lol.id','[=]','session.id']]],Base\Sql::option())['sql'] === '`lol` ON(lol.`id` = session.id)');
		\assert(Base\Sql::join(['table'=>'lol','on'=>[['lol.id','=','session.id']]],Base\Sql::option(['prepare'=>false]))['sql'] === "`lol` ON(lol.`id` = 'session.id')");

		// innerJoin
		\assert(\count(Base\Sql::innerJoin(['james',['active'=>1,'james.tst'=>'deux']])) === 1);

		// outerJoin
		\assert(empty(Base\Sql::outerJoin(['table'=>'james','on'=>['active'=>1,'james.tst'=>'deux']])['table']));

		// where
		\assert(Base\Sql::where([[30,'`between`',['userAdd','userModify']]])['sql'] === "30 BETWEEN `userAdd` AND `userModify`");
		\assert(Base\Sql::where([['id','`between`',[20,30]]])['sql'] === '`id` BETWEEN 20 AND 30');
		\assert(Base\Sql::where([['id','`between`',['james',3]]])['sql'] === '`id` BETWEEN `james` AND 3');
		\assert(Base\Sql::where([['name','findInSetOrNull',3]])['sql'] === "(FIND_IN_SET(3, `name`) OR `name` IS NULL)");
		\assert(Base\Sql::where([['id','in',[]],['james','=',2]])['sql'] === '`james` = 2');
		\assert(Base\Sql::where([['james','=',2],['id','in',[]]])['sql'] === '`james` = 2');
		\assert(Base\Sql::where([true,'id'=>2],Base\Sql::option())['id'] === 2);
		\assert(Base\Sql::where(['active'=>1])['sql'] === '`active` = 1');
		\assert(\strlen(Base\Sql::where(['active'=>1,'OR','(','james'=>'deux','(','ok'=>'lol'],Base\Sql::option())['sql']) >= 58);
		\assert(Base\Sql::where("id=test AND james='2'")['sql'] === "id=test AND james='2'");
		\assert(Base\Sql::where([['active','[=]','james.bla']])['sql'] === '`active` = james.bla');
		\assert(Base\Sql::where(['active'=>[1,'james',3],['active','>','james2']])['sql'] === "`active` IN(1, 'james', 3) AND `active` > 'james2'");
		\assert(Base\Sql::where([true,'id'=>3],Base\Sql::option())['sql'] === '`active` = 1 AND `id` = 3');
		\assert(Base\Sql::where([true,3],Base\Sql::option())['sql'] === '`active` = 1 AND `id` = 3');
		\assert(Base\Sql::where([true,[1,2,3],Base\Sql::option()],Base\Sql::option())['sql'] === '`active` = 1 AND `id` IN(1, 2, 3)');
		\assert(Base\Sql::where([['active','[=]','james.bla']],Base\Sql::option())['sql'] === '`active` = james.bla');
		\assert(Base\Sql::where(['active'=>null])['sql'] === '`active` IS NULL');
		\assert(Base\Sql::where(['active'=>true])['sql'] === "(`active` != '' AND `active` IS NOT NULL)");
		\assert(Base\Sql::where(['active'=>false])['sql'] === "(`active` = '' OR `active` IS NULL)");
		\assert(Base\Sql::where(['active'=>[1,2,3]])['sql'] === "`active` IN(1, 2, 3)");
		\assert(\strlen(Base\Sql::where(['active'=>['test'=>'ok','lol'=>'yeah']])['sql']) >= 20);
		\assert(Base\Sql::where([['active','=',null]])['sql'] === "`active` IS NULL");
		\assert(Base\Sql::where([['active','=',true]])['sql'] === "`active` = 1");
		\assert(Base\Sql::where([['active','=',false]])['sql'] === "`active` = 0");
		\assert(Base\Sql::where(2,Base\Sql::option())['whereOnlyId'] === true);
		\assert(Base\Sql::where([1,2,3],Base\Sql::option())['whereOnlyId'] === true);
		\assert(Base\Sql::where(['id'=>2],Base\Sql::option())['id'] === 2);
		\assert(Base\Sql::where(['id'=>[1,2,3]],Base\Sql::option())['whereOnlyId'] === true);
		\assert(Base\Sql::where(['id'=>2,'james'=>'ok'],Base\Sql::option())['whereOnlyId'] === false);
		\assert(Base\Sql::where(['id'=>[1,2,3],'james'=>'ok'],Base\Sql::option())['whereOnlyId'] === false);
		\assert(Base\Sql::where([['id','=',2],['test','=','james']],Base\Sql::option())['whereOnlyId'] === false);
		\assert(Base\Sql::where([['id','in',2]])['sql'] === '`id` IN(2)');
		\assert(Base\Sql::where([['id','like',2]])['sql'] === "`id` LIKE concat('%', 2, '%')");
		\assert(Base\Sql::where([['id','b|like',2]])['sql'] === "BINARY `id` LIKE concat('%', 2, '%')");
		\assert(Base\Sql::where([['id','b,l|like',2]])['sql'] === "BINARY LOWER(`id`) LIKE concat('%', 2, '%')");
		\assert(Base\Sql::where([['id','findInSet',[1,2,3]]])['sql'] === "(FIND_IN_SET(1, `id`) AND FIND_IN_SET(2, `id`) AND FIND_IN_SET(3, `id`))");
		\assert(Base\Sql::where([['id','or|findInSet',[1,2,3]]])['sql'] === "(FIND_IN_SET(1, `id`) OR FIND_IN_SET(2, `id`) OR FIND_IN_SET(3, `id`))");
		\assert(Base\Sql::where(['(',['ok','=',2]])['sql'] === '(`ok` = 2)');
		\assert(Base\Sql::where([['james',null,'what']])['sql'] === '`james` IS NULL');
		\assert(Base\Sql::where([['james','empty','what']])['sql'] === "(`james` = '' OR `james` IS NULL)");
		\assert(Base\Sql::where(['id'=>3,'&&','james'=>2,'XOR','lol'=>3])['sql'] === '`id` = 3 && `james` = 2 XOR `lol` = 3');
		\assert(Base\Sql::where([['id','b|=','bla'],['id','b|in',[1,2,3]],['id','b|findInSet','OK']])['sql'] === "BINARY `id` = 'bla' AND BINARY `id` IN(1, 2, 3) AND FIND_IN_SET('OK', BINARY `id`)");
		\assert(Base\Sql::where([['id','l,b|=','james']])['sql'] === "BINARY LOWER(`id`) = LOWER('james')");
		\assert(Base\Sql::where([['username','l|notIn',['NOBODY','ADMIN']]])['sql'] === "LOWER(`username`) NOT IN(LOWER('NOBODY'), LOWER('ADMIN'))");
		\assert(Base\Sql::where([['id','in',[]]])['sql'] === '');
		\assert(Base\Sql::where([['id',23]])['sql'] === "`id` = 23");

		// whereDefault
		\assert(Base\Sql::whereDefault([true,3],Base\Sql::option()) === ['active'=>1,1=>['id','=',3]]);
		\assert(Base\Sql::whereDefault(true,Base\Sql::option()) === ['active'=>1]);
		\assert(Base\Sql::whereDefault(2,Base\Sql::option()) === [['id','=',2]]);
		\assert(Base\Sql::whereDefault([1,2,3],Base\Sql::option()) === [['id','in',[1,2,3]]]);
		\assert(Base\Sql::whereDefault([true,'james'=>2],Base\Sql::option()) === ['active'=>1,'james'=>2]);
		\assert(Base\Sql::whereDefault([true,'active'=>2],Base\Sql::option()) === ['active'=>2]);
		\assert(Base\Sql::whereDefault([2],Base\Sql::option()) === [['id','=',2]]);

		// wherePrepare
		\assert(\count(Base\Sql::wherePrepare(['active'=>1])) === 1);
		\assert(\count(Base\Sql::wherePrepare(['active'=>1,'james'=>'deux'])) === 3);
		\assert(\count(Base\Sql::wherePrepare(['active'=>1,'OR','(','james'=>'deux','(','ok'=>'lol'])) === 9);
		\assert(\count(Base\Sql::wherePrepare([')','active'=>1])) === 1);
		\assert(Base\Sql::wherePrepare([['active','=',1]]) === [['active','=',1]]);
		\assert(Base\Sql::wherePrepare([['active',null]]) == [['active',null]]);
		\assert(Base\Sql::wherePrepare([true,[1,2,3]]) === []);
		\assert(\count(Base\Sql::wherePrepare(['active'=>1,'(','james'=>2,')'])) === 5);
		\assert(\count(Base\Sql::wherePrepare(['active'=>1,'OR','(','james'=>2,')','lala'=>3])) === 7);
		\assert(Base\Sql::wherePrepare([['active','=',false]]) === [['active','=',false]]);
		\assert(\count(Base\Sql::wherePrepare(['(',['ok','=',2]])) === 3);

		// wherePrepareOne
		\assert(Base\Sql::wherePrepareOne('active',1) === [['active','=',1]]);
		\assert(Base\Sql::wherePrepareOne('active',[1,2,3]) === [['active','in',[1,2,3]]]);
		\assert(Base\Sql::wherePrepareOne(0,'(') === [['(']]);
		\assert(Base\Sql::wherePrepareOne(0,'AND') === [['AND']]);
		\assert(Base\Sql::wherePrepareOne(0,'or') === [['or']]);
		\assert(Base\Sql::wherePrepareOne(0,['active','=',1]) === [['active','=',1]]);

		// whereCols
		\assert(Base\Sql::whereCols([['id','=',3],'james'=>2,['id','=',4],['ok','in',[1,3,3]]]) === ['id','james','ok']);

		// whereAppend
		\assert(Base\Sql::where(Base\Sql::whereAppend(true,['james'=>3],[['james','in',[1,2,3]]]))['sql'] === '`active` = 1 AND `james` = 3 AND `james` IN(1, 2, 3)');
		\assert(Base\Sql::where(Base\Sql::whereAppend(true,['james'=>[3,2,1]],[['james','in',[1,2,3]]]))['sql'] === '`active` = 1 AND `james` IN(3, 2, 1) AND `james` IN(1, 2, 3)');
		\assert(Base\Sql::where(Base\Sql::whereAppend(true,1))['sql'] === '`active` = 1 AND `id` = 1');

		// wherePrimary
		\assert(Base\Sql::wherePrimary([['id','=',3]],Base\Sql::option()) === ['id'=>3,'whereOnlyId'=>true]);
		\assert(Base\Sql::wherePrimary([['id','in',[1,2,'3']]],Base\Sql::option()) === ['id'=>[1,2,3],'whereOnlyId'=>true]);
		\assert(Base\Sql::wherePrimary([['id','in',[1,'test',3]]],Base\Sql::option()) === null);
		\assert(Base\Sql::wherePrimary([['id','=','3'],['ok','=','bla']],Base\Sql::option()) === ['id'=>3,'whereOnlyId'=>false]);

		// whereOne
		\assert(Base\Sql::whereOne("and")['sql'] === ' AND ');
		\assert(Base\Sql::whereOne("(")['sql'] === '(');

		// whereTwo
		\assert(Base\Sql::whereTwo('james',null)['sql'] === '`james` IS NULL');
		\assert(Base\Sql::whereTwo('james','notNull')['sql'] === '`james` IS NOT NULL');
		\assert(Base\Sql::whereTwo('james',true)['sql'] === "(`james` != '' AND `james` IS NOT NULL)");
		\assert(Base\Sql::whereTwo('james','notEmpty')['sql'] === "(`james` != '' AND `james` IS NOT NULL)");
		\assert(Base\Sql::whereTwo('james',false)['sql'] === "(`james` = '' OR `james` IS NULL)");
		\assert(Base\Sql::whereTwo('james','empty')['sql'] === "(`james` = '' OR `james` IS NULL)");
		\assert(Base\Sql::whereTwo('james',23)['sql'] === "`james` = 23");

		// whereThreeMethod

		// whereThree
		\assert(Base\Sql::whereThree('james','=',null)['sql'] === '`james` IS NULL');
		\assert(Base\Sql::whereThree("james",'[=]',Base\Sql::select("*","jacynthe")['sql'])['sql'] === "`james` = SELECT * FROM `jacynthe`");
		\assert(Base\Sql::whereThree("james","in",[1,2,3])['sql'] === '`james` IN(1, 2, 3)');
		\assert(Base\Sql::whereThree("james","notIn",[1,2,3])['sql'] === '`james` NOT IN(1, 2, 3)');
		\assert(Base\Sql::whereThree("james","`>=`","james.test")['sql'] === '`james` >= james.`test`');
		\assert(Base\Sql::whereThree('james','`notIn`',[1,2,'mymethod.james'])['sql'] === "`james` NOT IN(1, 2, mymethod.`james`)");
		\assert(Base\Sql::whereThree('james','[notIn]',[1,2,'mymethod.james'])['sql'] === "`james` NOT IN(1, 2, mymethod.james)");
		\assert(Base\Sql::whereThree('james','[b,l|notIn]',[2,'ok','test.col'])['sql'] === "BINARY LOWER(`james`) NOT IN(2, LOWER(ok), LOWER(test.col))");
		\assert(Base\Sql::whereThree('james','`b,l,or|notFindInSet`',[2,'ok','test.col'])['sql'] === "(!FIND_IN_SET(2, BINARY LOWER(`james`)) OR !FIND_IN_SET(LOWER(`ok`), BINARY LOWER(`james`)) OR !FIND_IN_SET(LOWER(test.`col`), BINARY LOWER(`james`)))");
		\assert(Base\Sql::whereThree('james','`b,l|notFindInSet`',[2,'ok','test.col'])['sql'] === "(!FIND_IN_SET(2, BINARY LOWER(`james`)) AND !FIND_IN_SET(LOWER(`ok`), BINARY LOWER(`james`)) AND !FIND_IN_SET(LOWER(test.`col`), BINARY LOWER(`james`)))");

		// whereIn
		\assert(Base\Sql::whereIn('james',[2,'james',3],'in')['sql'] === "`james` IN(2, 'james', 3)");
		\assert(Base\Sql::whereIn('james','test','notIn')['sql'] === "`james` NOT IN('test')");
		\assert(Base\Sql::whereIn('james',['test'=>2],'notIn')['sql'] === '');

		// whereBetween
		\assert(Base\Sql::whereBetween('james',[10,20],'between',['tick'=>true])['sql'] === "`james` BETWEEN 10 AND 20");
		\assert(Base\Sql::whereBetween('james',[10,20],'notBetween',['tick'=>true])['sql'] === "`james` NOT BETWEEN 10 AND 20");

		// whereFind
		\assert(Base\Sql::whereFind('james',3,'find')['sql'] === 'FIND_IN_SET(3, `james`)');
		\assert(Base\Sql::whereFind('james','james2','notFind')['sql'] === "!FIND_IN_SET('james2', `james`)");
		\assert(Base\Sql::whereFind('james',[3,'james2','james3'],'find')['sql'] === "(FIND_IN_SET(3, `james`) AND FIND_IN_SET('james2', `james`) AND FIND_IN_SET('james3', `james`))");
		\assert(Base\Sql::whereFind('james',[3,'james2','james3'],'find',['separator'=>'or'])['sql'] === "(FIND_IN_SET(3, `james`) OR FIND_IN_SET('james2', `james`) OR FIND_IN_SET('james3', `james`))");

		// whereFindOrNull
		\assert(Base\Sql::whereFindOrNull('james',3,'find')['sql'] === '(FIND_IN_SET(3, `james`) OR `james` IS NULL)');
		\assert(Base\Sql::whereFindOrNull('james',[3,4,'jaems2'],'find')['sql'] === "(FIND_IN_SET(3, `james`) OR `james` IS NULL) AND (FIND_IN_SET(4, `james`) OR `james` IS NULL) AND (FIND_IN_SET('jaems2', `james`) OR `james` IS NULL)");

		// whereLike
		\assert(Base\Sql::whereLike("james.bla","okkk",'like')['sql'] === "james.`bla` LIKE concat('%', 'okkk', '%')");
		\assert(Base\Sql::whereLike("james.bla","okkk",'notLike',['binary'=>true])['sql'] === "BINARY james.`bla` NOT LIKE concat('%', 'okkk', '%')");
		\assert(Base\Sql::whereLike("james.bla","okkk",'notLike%',['binary'=>true])['sql'] === "BINARY james.`bla` NOT LIKE concat('%', 'okkk')");
		\assert(\strlen(Base\Sql::whereLike("james.bla",['bla',2,3],'%like')['sql']) === 109);
		\assert(\strlen(Base\Sql::whereLike("james.bla",['bla',2,3],'%like',['separator'=>'or'])['sql']) === 107);
		\assert(Base\Sql::whereLike("james.bla","%",'like')['sql'] === "james.`bla` LIKE concat('%', '\%', '%')");
		\assert(Base\Sql::whereLike("james.bla","_",'like')['sql'] === "james.`bla` LIKE concat('%', '\_', '%')");
		\assert(Base\Sql::whereLike("james.bla","\\",'like')['sql'] === "james.`bla` LIKE concat('%', '\\\\\\\\', '%')");
		\assert(\current(Base\Sql::whereLike("james.bla","%",'like',Base\Sql::option())['prepare']) === "\%");
		\assert(\current(Base\Sql::whereLike("james.bla","_",'like',Base\Sql::option())['prepare']) === "\_");
		\assert(\current(Base\Sql::whereLike("james.bla","\\",'like',Base\Sql::option())['prepare']) === "\\\\");

		// whereDate
		\assert(Base\Sql::whereDate("james",Base\Date::mk(2017,1,2),"year")['sql'] === '(`james` >= 1483246800 AND `james` <= 1514782799)');
		\assert(Base\Sql::whereDate("james",Base\Date::mk(2017,2,2),"month")['sql'] === '(`james` >= 1485925200 AND `james` <= 1488344399)');
		\assert(Base\Sql::whereDate("james",Base\Date::mk(2017,1,2),"day")['sql'] === '(`james` >= 1483333200 AND `james` <= 1483419599)');
		\assert(Base\Sql::whereDate("james",Base\Date::mk(2017,1,2),"hour")['sql'] === '(`james` >= 1483333200 AND `james` <= 1483336799)');
		\assert(Base\Sql::whereDate("james",Base\Date::mk(2017,1,2),"minute")['sql'] === '(`james` >= 1483333200 AND `james` <= 1483333259)');
		\assert(Base\Sql::whereDate("james",["2017-01-02","ymd"],'day')['sql'] === '');
		\assert(Base\Sql::whereDate("james",[["2017-01-02","ymd"]],'day')['sql'] === '(`james` >= 1483333200 AND `james` <= 1483419599)');
		\assert(Base\Sql::whereDate('james',[Base\Date::mk(2017,1,2),Base\Date::mk(2017,1,3)],'month')['sql'] === '((`james` >= 1483246800 AND `james` <= 1485925199) AND (`james` >= 1483246800 AND `james` <= 1485925199))');
		\assert(Base\Sql::whereDate('james',[Base\Date::mk(2017,1,2),Base\Date::mk(2017,1,3)],'month',['separator'=>'or'])['sql'] === '((`james` >= 1483246800 AND `james` <= 1485925199) OR (`james` >= 1483246800 AND `james` <= 1485925199))');

		// group
		\assert(Base\Sql::group("test, test2.test, test")['sql'] === 'test, test2.test, test');
		\assert(Base\Sql::group(['test.test2','james'])['sql'] === 'test.`test2`, `james`');
		\assert(Base\Sql::group(true)['sql'] === '');

		// order
		\assert(Base\Sql::order(['test'=>true,'james'=>true])['sql'] === '`test` ASC, `james` ASC');
		\assert(Base\Sql::order(['test'=>'ASC','james','rand()','ok.test'=>'desc'],Base\Sql::option())['sql'] === '`test` ASC, `james` ASC, rand(), ok.`test` DESC');
		\assert(Base\Sql::order("test ASC, james DESC, rand()",Base\Sql::option())['sql'] === 'test ASC, james DESC, rand()');
		\assert(Base\Sql::order([['test','asc'],['order'=>'test2','direction'=>'asc']])['sql'] === '`test` ASC, `test2` ASC');
		\assert(Base\Sql::order([['james','findInSet','test']])['sql'] === 'FIND_IN_SET(`test`, `james`)');
		\assert(Base\Sql::order([[5,'findInSet','james']])['sql'] === 'FIND_IN_SET(`james`, `5`)');

		// orderPrepare
		\assert(Base\Sql::orderPrepare(['rand()']) === [['rand()']]);
		\assert(Base\Sql::orderPrepare(['test']) === [['test']]);
		\assert(Base\Sql::orderPrepare(['test'=>true]) === [['test',true]]);
		\assert(Base\Sql::orderPrepare(['test'=>'james']) === [['test','james']]);
		\assert(Base\Sql::orderPrepare([['test','ASC']]) === [['test','ASC']]);

		// orderOne
		\assert(Base\Sql::orderOne('rand()')['sql'] === 'rand()');
		\assert(Base\Sql::orderOne('test')['sql'] === '`test` ASC');

		// orderOneTwo
		\assert(Base\Sql::orderTwo('test','ASC')['sql'] === '`test` ASC');
		\assert(Base\Sql::orderTwo('test','desc')['sql'] === '`test` DESC');
		\assert(Base\Sql::orderTwo('test','james')['sql'] === '`test` ASC');

		// orderThree
		\assert(Base\Sql::orderThree("james","find","lala.col")['sql'] === 'FIND_IN_SET(lala.`col`, `james`)');

		// orderFind
		\assert(Base\Sql::orderFind('james','lala.col','find')['sql'] === 'FIND_IN_SET(lala.`col`, `james`)');

		// limit
		\assert(Base\Sql::limit("1,2")['sql'] === '1,2');
		\assert(Base\Sql::limit([1,2])['sql'] === '1 OFFSET 2');
		\assert(Base\Sql::limit([1])['sql'] === '1');
		\assert(Base\Sql::limit(1)['sql'] === '1');
		\assert(Base\Sql::limit([true,2],Base\Sql::option())['sql'] === PHP_INT_MAX.' OFFSET 2');
		\assert(Base\Sql::limit([true,true],Base\Sql::option())['sql'] === PHP_INT_MAX.' OFFSET '.PHP_INT_MAX);
		\assert(Base\Sql::limit(0)['sql'] === '0');
		\assert(Base\Sql::limit('0')['sql'] === '0');
		\assert(Base\Sql::limit([0])['sql'] === '0');
		\assert(Base\Sql::limit([1=>2])['sql'] === '2');
		\assert(Base\Sql::limit([3=>8])['sql'] === '8 OFFSET 16');
		\assert(Base\Sql::limit(['offset'=>3,'limit'=>10])['sql'] === '10 OFFSET 3');
		\assert(Base\Sql::limit(['limit'=>10,'offset'=>3])['sql'] === '10 OFFSET 3');
		\assert(Base\Sql::limit(['page'=>3,'limit'=>25])['sql'] === '25 OFFSET 50');

		// limitPrepare
		\assert(Base\Sql::limitPrepare(['2,3']) === [3,2]);
		\assert(Base\Sql::limitPrepare([4=>3]) === [3,9]);
		\assert(Base\Sql::limitPrepare([2=>2]) === [2,2]);

		// limitPrepareOne
		\assert(Base\Sql::limitPrepareOne(3,4) === [4,8]);

		// limitPrepareTwo
		\assert(Base\Sql::limitPrepareTwo(['page'=>3,'limit'=>25]) === [25,50]);

		// insertSet
		\assert(Base\Sql::insertSet(['active'=>2,'james'=>3,'oK'=>null,'lol.james'=>true])['sql'] === '(`active`, `james`, `oK`, lol.`james`) VALUES (2, 3, NULL, 1)');
		\assert(Base\Sql::insertSet(['activezzz','testzz'])['sql'] === '');
		\assert(Base\Sql::insertSet([['wwactivezzz','wwwtestzz']])['sql'] === "(`wwactivezzz`) VALUES ('wwwtestzz')");
		\assert(Base\Sql::insertSet([])['sql'] === '() VALUES ()');
		\assert(Base\Sql::insertSet([['name','lower','TEST'],['id',4]])['sql'] === "(`name`, `id`) VALUES (LOWER('TEST'), 4)");

		// insertSetFields

		// setPrepare
		\assert(Base\Sql::setPrepare(['what','test'=>'ok',['active','replace','ok','wow']])[1] === ['active','replace','ok','wow']);
		\assert(\count(Base\Sql::setPrepare(['active'=>false,'james'=>[1,2,3],'oK'=>null,'lol.james'=>true])) === 4);

		// setValues

		// updateSet
		\assert(Base\Sql::updateSet([['active','lower','test'],['id',4]])['sql'] === "`active` = LOWER('test'), `id` = 4");
		\assert(Base\Sql::updateSet([['active','replace','test','test2']])['sql'] === "`active` = REPLACE(`active`,'test','test2')");
		\assert(Base\Sql::updateSet(['active'=>false,'james'=>[1,2,3],'oK'=>null,'lol.james'=>true])['sql'] === "`active` = 0, `james` = '1,2,3', `oK` = NULL, lol.`james` = 1");
		\assert(Base\Sql::updateSet(['active'=>2,'james'=>3,'oK'=>null,'lol.james'=>true])['sql'] === '`active` = 2, `james` = 3, `oK` = NULL, lol.`james` = 1');
		\assert(\count(Base\Sql::updateSet(['james'=>[1,2,'name']],Base\Sql::option())['prepare']) === 1);
		\assert(Base\Sql::updateSet(['active'=>2,'james'=>3,'oK'=>null,'lol.james'=>true])['sql'] === '`active` = 2, `james` = 3, `oK` = NULL, lol.`james` = 1');
		\assert(Base\Sql::updateSet(['active'=>null,'james'=>true,'ok'=>false])['sql'] === '`active` = NULL, `james` = 1, `ok` = 0');

		// setOne
		\assert(Base\Sql::setOne(2)['sql'] === '2');

		// setTwo
		\assert(Base\Sql::setTwo("lower",24)['sql'] === "LOWER(24)");

		// setThree
		\assert(Base\Sql::setThree('james','replace','from','to')['sql'] === "REPLACE(`james`,'from','to')");

		// setReplace
		\assert(Base\Sql::setReplace('james','from','to','replace')['sql'] === "REPLACE(`james`,'from','to')");

		// col
		\assert(Base\Sql::col(['james'],Base\Sql::option())['sql'] === '');
		\assert(Base\Sql::col(['james','LOLLL'],Base\Sql::option())['sql'] === '');
		\assert(Base\Sql::col(['james','varchar'],Base\Sql::option())['sql'] === '`james` VARCHAR(255) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['james','varchar','length'=>55,'default'=>'james','null'=>false],Base\Sql::option(['prepare'=>false]))['sql'] === "`james` VARCHAR(55) NOT NULL DEFAULT 'james'");
		\assert(Base\Sql::col(['james','int'],Base\Sql::option())['sql'] === '`james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['james','int','length'=>20,'default'=>3,'autoIncrement'=>true,'after'=>'james'],Base\Sql::option())['sql'] === '`james` INT(20) NULL DEFAULT 3 AUTO_INCREMENT AFTER `james`');
		\assert(Base\Sql::col(['james','int'],Base\Sql::option(['type'=>'addCol']))['sql'] === 'ADD COLUMN `james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['james','int'],Base\Sql::option(['type'=>'alterCol']))['sql'] === 'CHANGE `james` `james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['id','int','length'=>11,'autoIncrement'=>true,'null'=>null])['sql'] === '`id` INT(11) AUTO_INCREMENT');

		// makeCol
		\assert(Base\Sql::col(['james','int'],Base\Sql::option(['type'=>'createCol']))['sql'] === '`james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['james','int'],Base\Sql::option(['type'=>'addCol']))['sql'] === 'ADD COLUMN `james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::col(['james','int'],Base\Sql::option(['type'=>'alterCol']))['sql'] === 'CHANGE `james` `james` INT(11) NULL DEFAULT NULL');

		// createCol
		\assert(Base\Sql::createCol(['james','varchar','length'=>55,'default'=>'james','null'=>false],Base\Sql::option(['prepare'=>false]))['sql'] === "`james` VARCHAR(55) NOT NULL DEFAULT 'james'");
		\assert(Base\Sql::createCol([['james','varchar'],['name'=>'lol','type'=>'int']])['sql'] === '`james` VARCHAR(255) NULL DEFAULT NULL, `lol` INT(11) NULL DEFAULT NULL');

		// addCol
		\assert(Base\Sql::addCol(['james','varchar','length'=>55,'default'=>'james','null'=>false],Base\Sql::option(['prepare'=>false]))['sql'] === "ADD COLUMN `james` VARCHAR(55) NOT NULL DEFAULT 'james'");
		\assert(Base\Sql::addCol([['james','varchar'],['name'=>'lol','type'=>'int']])['sql'] === 'ADD COLUMN `james` VARCHAR(255) NULL DEFAULT NULL, ADD COLUMN `lol` INT(11) NULL DEFAULT NULL');

		// alterCol
		\assert(Base\Sql::alterCol(['james','int'])['sql'] === 'CHANGE `james` `james` INT(11) NULL DEFAULT NULL');
		\assert(Base\Sql::alterCol(['james','int','rename'=>'james2','length'=>25])['sql'] === "CHANGE `james` `james2` INT(25) NULL DEFAULT NULL");

		// dropCol
		\assert(Base\Sql::dropCol("test")['sql'] === 'test');
		\assert(Base\Sql::dropCol(['test'])['sql'] === "DROP COLUMN `test`");
		\assert(Base\Sql::dropCol(['test_[lang]','test2.lala'])['sql'] === "DROP COLUMN `test_en`, DROP COLUMN test2.`lala`");

		// key
		\assert(Base\Sql::key(['key'=>'key','col'=>'test'])['sql'] === 'KEY (`test`)');
		\assert(Base\Sql::key(['primary','test'])['sql'] === 'PRIMARY KEY (`test`)');
		\assert(Base\Sql::key(['primary',null])['sql'] === '');
		\assert(Base\Sql::key(['unique','test',['james.lol','ok']])['sql'] === 'UNIQUE KEY `test` (james.`lol`, `ok`)');
		\assert(Base\Sql::key(['unique','test',['james.lol','ok']])['sql'] === 'UNIQUE KEY `test` (james.`lol`, `ok`)');
		\assert(Base\Sql::key(['unique',null,['james.lol','ok']])['sql'] === '');
		\assert(Base\Sql::key(['unique','ok'])['sql'] === 'UNIQUE KEY `ok` (`ok`)');
		\assert(Base\Sql::key(['unique','ok','james'])['sql'] === "UNIQUE KEY `ok` (`james`)");

		// makeKey
		\assert(Base\Sql::makeKey(["primary","id"],Base\Sql::option(['type'=>'createKey']))['sql'] === 'PRIMARY KEY (`id`)');
		\assert(Base\Sql::makeKey(["primary","id"],Base\Sql::option(['type'=>'addKey']))['sql'] === 'ADD PRIMARY KEY (`id`)');

		// createKey
		\assert(Base\Sql::createKey(['test'])['sql'] === '');
		\assert(Base\Sql::createKey(["primary","id"])['sql'] === 'PRIMARY KEY (`id`)');
		\assert(Base\Sql::createKey(["unique","james",["id",'james']])['sql'] === 'UNIQUE KEY `james` (`id`, `james`)');
		\assert(Base\Sql::createKey(["key","id"])['sql'] === 'KEY (`id`)');
		\assert(Base\Sql::createKey([["key","id"],["unique","james",["id",'james']]])['sql'] === 'KEY (`id`), UNIQUE KEY `james` (`id`, `james`)');

		// addKey
		\assert(Base\Sql::addKey("test bla")['sql'] === 'test bla');
		\assert(Base\Sql::addKey(['test'])['sql'] === '');
		\assert(Base\Sql::addKey(["primary","id"])['sql'] === 'ADD PRIMARY KEY (`id`)');
		\assert(Base\Sql::addKey(["unique","james",["id",'james']])['sql'] === 'ADD UNIQUE KEY `james` (`id`, `james`)');
		\assert(Base\Sql::addKey([["key","id"],["unique","james",["id",'james']]])['sql'] === 'ADD KEY (`id`), ADD UNIQUE KEY `james` (`id`, `james`)');

		// dropKey
		\assert(Base\Sql::dropKey("test")['sql'] === 'test');
		\assert(Base\Sql::dropKey(['test'])['sql'] === 'DROP KEY `test`');
		\assert(Base\Sql::dropKey(['test_[lang]','test2.lala'])['sql'] === 'DROP KEY `test_en`, DROP KEY test2.`lala`');

		// createEnd
		\assert(Base\Sql::createEnd(Base\Sql::option())['sql'] === ') ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

		// prepareDefault

		// make
		\assert(Base\Sql::make('select',['*','user',['active'=>1,'james'=>2],['active'=>'DESC'],2])['sql'] === 'SELECT * FROM `user` WHERE `active` = 1 AND `james` = 2 ORDER BY `active` DESC LIMIT 2');
		\assert(Base\Sql::make('select',['*','where'=>true]) === null);
		\assert(Base\Sql::make('select',['*','table'=>null]) === null);
		\assert(Base\Sql::make('select',['*','ok'])['sql'] === 'SELECT * FROM `ok`');
		\assert(Base\Sql::make('select',['*','ok'])['type'] === 'select');
		\assert(Base\Sql::make('select',['*','ok'])['table'] === 'ok');
		\assert(\strlen(Base\Sql::make('select',['join'=>['table'=>'lol','on'=>true],'*','ok','order'=>['type'=>'asc'],'where'=>true])['sql']) === 85);
		\assert(\strlen(Base\Sql::make('select',['outerJoin'=>['table'=>'lol','on'=>true],'*','ok','order'=>['type'=>'asc'],'where'=>true])['sql']) === 96);
		\assert(Base\Sql::make('select',['*','user',['active'=>1,'james'=>'tes\'rttté'],['active'=>'DESC'],2],['prepare'=>false])['sql'] === "SELECT * FROM `user` WHERE `active` = 1 AND `james` = 'tes\'rttté' ORDER BY `active` DESC LIMIT 2");
		\assert(Base\Sql::make('select',[true,'james3',['id'=>3]])['table'] === 'james3');
		\assert(Base\Sql::make('select',[true,'james3',[true,'id'=>3]])['id'] === 3);
		\assert(Base\Sql::make('select',[true,'james3',[true,'id'=>[1,2,3]]])['id'] === [1,2,3]);
		\assert(Base\Sql::make('create',["james2",["james",'int'],[['unique','lol','james'],['primary','id']]],['createNotExists'=>true])['sql'] === 'CREATE TABLE IF NOT EXISTS `james2` (`james` INT(11) NULL DEFAULT NULL, UNIQUE KEY `lol` (`james`), PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');
		\assert(\count(Base\Sql::make('select',[true,'james3',['name'=>'what'],'prepare'=>['test'=>'ok']])['prepare']) === 2);
		\assert(Base\Sql::make('select',Base\Sql::makeParses('select',['*','table',2,'id',3]))['sql'] === 'SELECT * FROM `table` WHERE `id` = 2 ORDER BY id LIMIT 3');
		\assert(Base\Sql::make('select',['what'=>'*','table'=>'ok','where'=>'id="2"'])['sql'] === 'SELECT * FROM `ok` WHERE id="2"');
		\assert(Base\Sql::make('select',['*','james',null,null,0])['sql'] === "SELECT * FROM `james` LIMIT 0");
		\assert(\count(Base\Sql::make('select',['*','james',[],null,0])) === 3);
		\assert(Base\Sql::make('select',['*','james',['active'=>1,[12312312,'`between`',['from','to']]]])['sql'] === "SELECT * FROM `james` WHERE `active` = 1 AND 12312312 BETWEEN `from` AND `to`");
		\assert(\strlen(Base\Sql::make('select',['*','james',['active'=>1,'date'=>Base\Date::timestamp()]])['sql']) === 64);

		// makeParses
		\assert(Base\Sql::makeParses('select',['*','table',2,'id',3]) === ['what'=>'*','table'=>'table','where'=>2,'order'=>'id','limit'=>3]);

		// makeParse
		\assert(Base\Sql::makeParse('select','what',['*','user',['active'=>1,'james'=>2],['active'=>'DESC'],2]) === '*');
		\assert(Base\Sql::makeParse('select','where',['*','user',['active'=>1,'james'=>2],['active'=>'DESC'],2]) === ['active'=>1,'james'=>2]);
		\assert(Base\Sql::makeParse('select','wherez',['*','user',['active'=>1,'james'=>2],['active'=>'DESC'],2]) === null);

		// makeSelectFrom
		$insert = ['table',['ok'=>2,'id'=>4]];
		$update = ['table',['james'=>'ok'],3,['name'=>'asc'],2];
		$delete = ['table',4,['name'=>'asc'],2];
		\assert(Base\Sql::makeSelectFrom('update',$update)['sql'] === 'SELECT * FROM `table` WHERE `id` = 3 ORDER BY `name` ASC LIMIT 2');
		\assert(Base\Sql::makeSelectFrom('delete',$delete)['sql'] === 'SELECT * FROM `table` WHERE `id` = 4 ORDER BY `name` ASC LIMIT 2');
		\assert(Base\Sql::makeSelectFrom('insert',$insert)['sql'] === 'SELECT * FROM `table` WHERE `ok` = 2 AND `id` = 4 LIMIT 1');
		\assert(Base\Sql::makeSelectFrom('insert',$insert,Base\Sql::option())['sql'] === 'SELECT * FROM `table` WHERE `ok` = 2 AND `id` = 4 ORDER BY `id` DESC LIMIT 1');

		// makeSelect
		\assert(\strlen(Base\Sql::makeSelect(["*","user",['active'=>'name'],["order"=>"Desc","active"],[4,4]])['sql']) >= 92);
		\assert(\count(Base\Sql::makeSelect(["*","user",[],["order"=>"Desc","active"],[4,4]])) === 3);

		// makeShow
		\assert(Base\Sql::makeShow(["TABLES"])['sql'] === 'SHOW TABLES');

		// makeInsert
		\assert(\strlen(Base\Sql::makeInsert(['user',['active'=>1,'james'=>null,'OK.james'=>'LOLÉ']])['sql']) >= 77);
		\assert(Base\Sql::makeInsert(['user',[]])['sql'] === 'INSERT INTO `user` () VALUES ()');

		// makeUpdate
		\assert(Base\Sql::makeUpdate(["james",['james'=>2,'lala.ok'=>null],['active'=>1],['od'=>'desc'],3])['sql'] === 'UPDATE `james` SET `james` = 2, lala.`ok` = NULL WHERE `active` = 1 ORDER BY `od` DESC LIMIT 3');

		// makeDelete
		\assert(Base\Sql::makeDelete(["james",['active'=>1,'james'=>2],['id'],3])['sql'] === 'DELETE FROM `james` WHERE `active` = 1 AND `james` = 2 ORDER BY `id` ASC LIMIT 3');

		// makeCreate
		\assert(Base\Sql::makeCreate(["james2",[['james','int'],['ok','varchar']],[['unique','lol','james'],['primary','id']]])['sql'] === 'CREATE TABLE `james2` (`james` INT(11) NULL DEFAULT NULL, `ok` VARCHAR(255) NULL DEFAULT NULL, UNIQUE KEY `lol` (`james`), PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

		// makeAlter
		\assert(Base\Sql::makeAlter(["james",null,null,null,null,null])['sql'] === 'ALTER TABLE `james`');
		\assert(Base\Sql::makeAlter(["james"])['sql'] === 'ALTER TABLE `james`');
		\assert(Base\Sql::makeAlter(["james"])['table'] === 'james');

		// makeTruncate
		\assert(Base\Sql::makeTruncate(["james"])['sql'] === 'TRUNCATE TABLE `james`');

		// makeDrop
		\assert(Base\Sql::makeDrop(["okkk"])['sql'] === 'DROP TABLE `okkk`');

		// select
		\assert(\strlen(Base\Sql::select("*","user",['active'=>'name'],["order"=>"Desc","active"],[4,4])['sql']) >= 92);
		\assert(\strlen(Base\Sql::select([true,'james'=>['distinct','james']],"james_[lang]",[true,'or','(',2,[2,3,4],'james_[lang]'=>4,')',['james','findInSet',[5,6]]],true,true)['sql']) > 220);

		// show
		\assert(Base\Sql::show("TABLES")['sql'] === 'SHOW TABLES');

		// insert
		\assert(\strlen(Base\Sql::insert('user',['active'=>1,'james'=>null,'OK.james'=>'LOLÉ'])['sql']) >= 77);

		// update
		\assert(Base\Sql::update("james",['james'=>2,'lala.ok'=>null],['active'=>1],['od'=>'desc'],3)['sql'] === 'UPDATE `james` SET `james` = 2, lala.`ok` = NULL WHERE `active` = 1 ORDER BY `od` DESC LIMIT 3');
		\assert(Base\Sql::update("james",['james'=>2,'lala.ok'=>null],['active'=>1],['od'=>'desc'],3)['select']['sql'] === "SELECT * FROM `james` WHERE `active` = 1 ORDER BY `od` DESC LIMIT 3");
		\assert(\count(Base\Sql::update("james",['james'=>2,'lala.ok'=>null],['active'=>'ok','id'=>5],['od'=>'desc'],3)['select']) === 6);
		\assert(Base\Sql::select("*","james",[2])['sql'] === 'SELECT * FROM `james` WHERE `id` = 2');
		\assert(Base\Sql::update('james',['james'=>2],[2])['sql'] === 'UPDATE `james` SET `james` = 2 WHERE `id` = 2');

		// delete
		\assert(Base\Sql::delete("james",['active'=>1,'james'=>2],['id'],3)['sql'] === 'DELETE FROM `james` WHERE `active` = 1 AND `james` = 2 ORDER BY `id` ASC LIMIT 3');

		// create
		\assert(Base\Sql::create("james2",[['james','int'],['ok','varchar']],[['unique','lol','james'],['primary','id']])['sql'] === 'CREATE TABLE `james2` (`james` INT(11) NULL DEFAULT NULL, `ok` VARCHAR(255) NULL DEFAULT NULL, UNIQUE KEY `lol` (`james`), PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

		// alter
		\assert(Base\Sql::alter("james",["james",'int'],["unique","lao",["james","id"]])['sql'] === 'ALTER TABLE `james` ADD COLUMN `james` INT(11) NULL DEFAULT NULL, ADD UNIQUE KEY `lao` (`james`, `id`)');
		\assert(Base\Sql::alter("james",null,["unique","lao",["james","id"]],[["james","int"],["bla","varchar",'rename'=>'LOL']])['sql'] === 'ALTER TABLE `james` ADD UNIQUE KEY `lao` (`james`, `id`), CHANGE `james` `james` INT(11) NULL DEFAULT NULL, CHANGE `bla` `LOL` VARCHAR(255) NULL DEFAULT NULL');
		\assert(Base\Sql::alter("james",null,null,null,["test","ok"],"JAMES SQL")['sql'] === 'ALTER TABLE `james` DROP COLUMN `test`, DROP COLUMN `ok`, JAMES SQL');
		\assert(Base\Sql::alter("james",null,null,null,null,null)['sql'] === 'ALTER TABLE `james`');

		// truncate
		\assert(Base\Sql::truncate("james")['sql'] === 'TRUNCATE TABLE `james`');

		// drop
		\assert(Base\Sql::drop("okkk")['sql'] === 'DROP TABLE `okkk`');

		// count
		\assert(Base\Sql::selectCount("user")['sql'] === 'SELECT COUNT(`id`) FROM `user`');

		// makeSelectCount
		\assert(Base\Sql::makeSelectCount(['my',2])['sql'] === 'SELECT COUNT(`id`) FROM `my` WHERE `id` = 2');

		// makeSelectAll
		\assert(Base\Sql::makeSelectAll(['james',2])['sql'] === 'SELECT * FROM `james` WHERE `id` = 2');
		\assert(Base\Sql::makeSelectAll(['james',['test'=>null]])['sql'] === "SELECT * FROM `james` WHERE `test` IS NULL");
		\assert(Base\Sql::makeSelectAll(['james',['test'=>true]])['sql'] === "SELECT * FROM `james` WHERE (`test` != '' AND `test` IS NOT NULL)");
		\assert(Base\Sql::makeSelectAll(['james',['test'=>false]])['sql'] === "SELECT * FROM `james` WHERE (`test` = '' OR `test` IS NULL)");
		\assert(Base\Sql::makeSelectAll(['james',[['test',true]]])['sql'] === "SELECT * FROM `james` WHERE (`test` != '' AND `test` IS NOT NULL)");
		\assert(Base\Sql::makeSelectAll(['james',[['test','empty']]])['sql'] === "SELECT * FROM `james` WHERE (`test` = '' OR `test` IS NULL)");
		\assert(Base\Sql::makeSelectAll(['james',[['test',null]]])['sql'] === "SELECT * FROM `james` WHERE `test` IS NULL");
		\assert(Base\Sql::makeSelectAll(['james',[['test','notNull']]])['sql'] === "SELECT * FROM `james` WHERE `test` IS NOT NULL");
		\assert(Base\Sql::makeSelectAll(['james',[['test',false]]])['sql'] === "SELECT * FROM `james` WHERE (`test` = '' OR `test` IS NULL)");
		\assert(Base\Sql::makeSelectAll(['james',[['test','notEmpty']]])['sql'] === "SELECT * FROM `james` WHERE (`test` != '' AND `test` IS NOT NULL)");

		// makeSelectFunction
		\assert(Base\Sql::makeSelectFunction('col','sum',['james',2])['sql'] === 'SELECT SUM(`col`) FROM `james` WHERE `id` = 2');

		// makeSelectDistinct
		\assert(Base\Sql::makeSelectDistinct('col',['james',2])['sql'] === 'SELECT DISTINCT `col` FROM `james` WHERE `id` = 2');

		// makeSelectColumn
		\assert(Base\Sql::makeSelectColumn('col',['james',2])['sql'] === 'SELECT `col` FROM `james` WHERE `id` = 2');
		\assert(Base\Sql::makeSelectColumn(['what','sum()'],['james'])['sql'] === 'SELECT SUM(`what`) FROM `james`');

		// makeselectKeyPair
		\assert(Base\Sql::makeselectKeyPair('col','col2',['james',2])['sql'] === "SELECT `col`, `col2` FROM `james` WHERE `id` = 2");

		// makeselectPrimary
		\assert(Base\Sql::makeselectPrimary(['table',2],['primary'=>'idsz'])['sql'] === 'SELECT idsz FROM `table` WHERE `idsz` = 2');

		// makeselectPrimaryPair
		\assert(Base\Sql::makeselectPrimaryPair('col',['table',2])['sql'] === 'SELECT `id`, `col` FROM `table` WHERE `id` = 2');

		// makeSelectSegment
		\assert(Base\Sql::makeSelectSegment("[col] + [name_%lang%] [col]  v [col] [id]",['james',2])['sql'] === "SELECT `id`, `col`, `name_en` FROM `james` WHERE `id` = 2");
		\assert(Base\Sql::makeSelectSegment("[col] + [name_%lang%] [id]",['james',2])['sql'] === 'SELECT `id`, `col`, `name_en` FROM `james` WHERE `id` = 2');

		// makeShowDatabase
		\assert(Base\Sql::makeShowDatabase()['sql'] === 'SHOW DATABASES');
		\assert(Base\Sql::makeShowDatabase('quid')['sql'] === "SHOW DATABASES LIKE 'quid'");

		// makeShowVariable
		\assert(Base\Sql::makeShowVariable()['sql'] === "SHOW VARIABLES");
		\assert(Base\Sql::makeShowVariable('automatic')['sql'] === "SHOW VARIABLES LIKE 'automatic'");

		// makeShowTable
		\assert(Base\Sql::makeShowTable()['sql'] === 'SHOW TABLES');
		\assert(Base\Sql::makeShowTable('basePdo')['sql'] === "SHOW TABLES LIKE 'basePdo'");

		// makeShowTableStatus
		\assert(Base\Sql::makeShowTableStatus()['sql'] === 'SHOW TABLE STATUS');
		\assert(Base\Sql::makeShowTableStatus('test_[lang]')['sql'] === "SHOW TABLE STATUS LIKE 'test_en'");

		// makeShowTableColumn
		\assert(Base\Sql::makeShowTableColumn('myTable','lol')['sql'] === "SHOW COLUMNS FROM `myTable` WHERE FIELD = 'lol'");
		\assert(Base\Sql::makeShowTableColumn('myTable')['sql'] === 'SHOW COLUMNS FROM `myTable`');
		\assert(Base\Sql::makeShowTableColumn('myTable')['table'] === 'myTable');

		// makeAlterAutoIncrement
		\assert(Base\Sql::makeAlterAutoIncrement('table',3)['sql'] === 'ALTER TABLE `table` AUTO_INCREMENT = 3');
		\assert(Base\Sql::makeAlterAutoIncrement('table',3)['table'] === 'table');

		// parseReturn
		\assert(Base\Sql::parseReturn('SELECT * from james')['type'] === 'select');
		\assert(Base\Sql::parseReturn(['UPDATE james',['ok'=>'lol']])['prepare'] === ['ok'=>'lol']);
		\assert(Base\Sql::parseReturn(['sql'=>'UPDATE james','prepare'=>['ok'=>'lol']])['sql'] === 'UPDATE james');

		// type
		\assert(Base\Sql::type("ALTER TABLE `james` DROP COLUMN `test`") === 'alter');
		\assert(Base\Sql::type(" SELECT TABLE `james` DROP COLUMN `test`") === 'select');

		// emulate
		$sql = 'SELECT * FROM `user` WHERE `active` = :APCBIE18 AND `test` = 2';
		$prepare = ['APCBIE18'=>'name'];
		\assert(Base\Sql::emulate($sql,$prepare) === "SELECT * FROM `user` WHERE `active` = 'name' AND `test` = 2");
		$sql = 'SELECT * FROM `user` WHERE `active` = :APCBIE18 AND `test` = 2';
		$prepare = ['APCBIE18'=>'na\me'];
		\assert(Base\Sql::emulate($sql,$prepare,null,true) === 'SELECT * FROM `user` WHERE `active` = \'na\\me\' AND `test` = 2');
		\assert(Base\Sql::emulate($sql,$prepare,null,false) === 'SELECT * FROM `user` WHERE `active` = \'na\\\\me\' AND `test` = 2');

		// debug
		\assert(Base\Sql::debug(Base\Sql::select("*","james",['name'=>'ok']))['emulate'] === "SELECT * FROM `james` WHERE `name` = 'ok'");

		// shortcut
		\assert(!empty(Base\Sql::allShortcuts()));
		\assert(Base\Sql::shortcuts(['test'=>'name_[lang]']) === ['test'=>'name_en']);
		\assert(Base\Sql::getShortcut('lang') === 'en');
		Base\Sql::setShortcut('james','ok');
		\assert(Base\Sql::getShortcut('james') === 'ok');
		Base\Sql::unsetShortcut('james');
		\assert(Base\Sql::shortcut("name_[lang]") === 'name_en');
		\assert(Base\Sql::shortcut(["name_[lang]"]) === ['name_en']);

		// option
		\assert(\count(Base\Sql::option(['primary'=>'iz'])) === 13);
		\assert(Base\Sql::isOption('primary') === true);
		\assert(Base\Sql::getOption('primary') === 'id');
		Base\Sql::setOption('test',true);
		Base\Sql::setOption('test2',true);
		Base\Sql::unsetOption('test');
		Base\Sql::unsetOption('test2');
		\assert(Base\Sql::option(['test'=>2])['test'] === 2);
		\assert(empty(Base\Sql::option()['test']));

		// cleanup
		
		return true;
	}
}
?>