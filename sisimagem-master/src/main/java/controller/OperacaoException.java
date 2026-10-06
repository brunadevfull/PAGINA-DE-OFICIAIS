package controller;

public class OperacaoException extends Exception {

	public OperacaoException(String msg) {
		super(msg);
	}

	public OperacaoException(Throwable e) {
		super(e);
	}

}
