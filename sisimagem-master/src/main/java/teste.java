import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.TimeZone;

import model.DAOTrim;

public class teste {

	/**
	 * @param args
	 * @throws ParseException
	 */
	public static void testaUsuario() {
		DAOTrim daoTrim = new DAOTrim();
		// System.out.println(daoTrim.validaUsuarioSenha("34",
		// "BCF5FA615779A978EF53D36AFAA08BF9692D8540057DEF0D183B26F4B9D01E110C711821FCEF29B33430A450B387AE5409816EC08495270F753C5C635D4359D7"));
	}

	public static void main(String[] args) throws ParseException {

		/*
		 * String teste2 = System.getProperty("user.home");
		 * System.out.println(File.separator+System.getProperty("user.home")+
		 * File.separator+"001.tif"+File.separator);
		 */
		testaUsuario();

	}

	public static void testaData() {
		SimpleDateFormat dataTime = new SimpleDateFormat("yyyyMMddHHmmss");
		dataTime.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));
		SimpleDateFormat ft = new SimpleDateFormat("EEEEEEE, d 'de' MMMMMM 'de' yyyy 'a' HH:mm:ss");

		ft.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));
		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));
		// UTC -2
		/*
		 * System.out.println(TimeZone.getTimeZone("GMT-2")); String [] t =
		 * TimeZone.getAvailableIDs(); for (int i=0; i< t.length;i++) {
		 * System.out.println(t[i]+TimeZone.getTimeZone(t[i])); }
		 */
		SimpleDateFormat teste = new SimpleDateFormat();
		// teste.setTimeZone(TimeZone.getTimeZone("GMT-2"));

		/*
		 * Date d = dataTime.parse("20110824125012");
		 * System.out.println(//"13/2/2014 12:50:34 - "+ ft.format(d)); Date
		 * dEntrada= dfEntrada.parse("22/04/2015 23:39:43");
		 * System.out.println(dataTime.format(dEntrada));
		 */
		/*
		 * d = dataTime.parse("20110824125130");
		 * System.out.println(//"26/9/2011 09:27:04  - "+ ft.format(d));
		 * 
		 * d = dataTime.parse("20110912193043");
		 * System.out.println(//"20/2/2015 15:18:25 - "+ ft.format(d));
		 * 
		 * d = dataTime.parse("20110913172743");
		 * System.out.println(//"16/04/2015 08:53:52 - "+ ft.format(d));
		 * 
		 * d = dataTime.parse("20150417113023");
		 * System.out.println(//"17/04/2015 08:30:23 - "+ ft.format(d));
		 * 
		 * d = dataTime.parse("20150417113842");
		 * System.out.println(//"17/04/2015 08:32:42 - "+ ft.format(d));
		 */
		// uri:599303 date: 20140213 155034 1 13/2/2014 12:50:34 azt
		// uri:290954 date: 20110926 122704 1 26/9/2011 09:27:04
		// uri: 610324 date: 20150220 181825 1 20/2/2015 15:18:25
		/*
		 * 612047 20150416 115352 1 16/04/2015 08:53:52 612157 20150417 113023 1
		 * 17/04/2015 08:30:23 612158 20150417 113842 1 17/04/2015 08:32:42
		 */
		/*
		 * 201108241250121 20110824125130 1 20110912193043 1 20110913172743 1
		 * 201109142042361 201108302150371 201109142033541 201109142031101
		 * 201109142034291 201109142038521 201109161333461 201109161338091
		 * 201109161338131 201109161338151 201108302039051 201108302140541
		 * 201109142042381 201109151228401 201109161335111 201109161335201
		 * 201109161335351 201109161338061 201109161338111 201109161338191
		 * 201109161338231
		 */

		// TODO Auto-generated method stub
		/*
		 * String teste= null; Integer i =1;
		 * System.out.println(teste!=null&&teste.equals(i.toString()));
		 * 
		 * Registro reg = new Registro();
		 * System.out.println(reg.lTipoRegistro.size());
		 */
		/*
		 * SimpleDateFormat dataTime = new SimpleDateFormat("yyyyMMddHHmmss");
		 * dataTime.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));
		 * 
		 * SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		 * dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT")); Date d = new
		 * Date(); d.setHours(8); System.out.println(dfEntrada.format(d));
		 */
		// Date dsaida = new Date(dfEntrada.format(d));
		// System.out.println(dataTime.format(dsaida));
	}
}
